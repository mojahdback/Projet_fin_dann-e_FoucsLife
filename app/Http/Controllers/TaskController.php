<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\GoalService;
use App\Services\TaskService;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected GoalService $goalService
    ){}


    public function index(Request $request)
    {
        $userId = session('auth_user_id');
        $period = $request->query('period' , 'all');

        $tasks = $period === 'all'
            ? $this->taskService->getAllForUser($userId)
            : $this->taskService->getByPeriod($userId , $period);

        return view('tasks.index' , compact('tasks' , 'period'));


    }

    public function create(Request $request)
    {
        $userId = session('auth_user_id');
        $goals  = $this->goalService->getActive($userId);

        $selectedGoalId = $request->query('goal_id');

        return view('tasks.create' , compact('goals' , 'selectedGoalId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'description'  => 'nullable|string',
            'goal_id'  => 'nullable|exists:goals,goal_id',
            'priority'  => 'required|in:low,medium,high',
            'period'  => 'required|in:day,week,month,year',
            'due_date'  => 'nullable|date',

        ]);

        $this->taskService->create(session('auth_user_id'), $validated);

        return redirect()->route('tasks.index')
            ->with('success' , 'Task created successfully.');
    }

    public function show(int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || !$this->taskService->authorizeUser($task, session('auth_user_id'))){
            abort(403);
        }

        $timeLogs = $this->taskService->getTimeLogs($task);

        return view('tasks.show' , compact('task' , 'timeLogs'));
    }

    public function edit(int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || !$this->taskService->authorizeUser($task , session('auth_user_id'))){
            abort(403);
        }

        $goals = $this->goalService->getActive(session('auth_user_id'));

        return view('tasks.edit' , compact('task' , 'goals'));

    }

    public function update(Request $request, int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || !$this->taskService->authorizeUser($task, session('auth_user_id'))){
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_id' => 'nullable|exists:goals,goal_id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,done,cancelled',
            'period'  => 'required|in:day,week,month,year',
            'due_date' => 'nullable|date',

        ]);

        $this->taskService->update($task , $validated);

        return redirect()->route('tasks.index')
            ->with('success' , 'Task update successfully');
    }


    public function destory(int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || $this->taskService->authorizeUser($task, session('auth_user_id')) ){
            abort(403);
        }

        $this->taskService->delete($task);

        return redirect()->route('tasks.index')
            ->with('success' , 'Task deleted successfully.');
    }

    public function startTimer(int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || !$this->taskService->authorizeUser($task, session('auth_user_id'))){
            abort(403);
        }

        $this->taskService->startTimer($task);

        return redirect()->route('tasks.show', $id)
            ->with('success' , 'Timer started.');
    }

    public function stopTimer(int $id)
    {
        $task = $this->taskService->findById($id);

        if(!$task || !$this->taskService->authorizeUser($task , session('auth_user_id'))){
            abort(403);
        }

        $this->taskService->stopTimer($task);

        return redirect()->route('tasks.show', $id)
            ->with('success' , 'Timer stopped.');
    }



}

