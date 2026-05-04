<?php

namespace App\Http\Controllers;

use App\Services\GoalService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected GoalService $goalService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();

         if (!$user) {
            abort(401);
        }

        $userId = $user->user_id;
        $filter = $request->query('filter', 'all');

        $tasks = match($filter) {
            'today' => $this->taskService->getForToday($userId),
            'week'  => $this->taskService->getForThisWeek($userId),
            default => $this->taskService->getAllForUser($userId),
        };

        // Group by status for Kanban
        $todo        = $tasks->where('status', 'todo')->values();
        $inProgress  = $tasks->where('status', 'in_progress')->values();
        $done        = $tasks->where('status', 'done')->values();

        return view('tasks.index', compact(
            'tasks', 'filter', 'todo', 'inProgress', 'done'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
           abort(401);
        }

        $goals = $this->goalService->getActive($user->user_id);
        $selectedGoalId = $request->query('goal_id');

        return view('tasks.create', compact('goals', 'selectedGoalId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|min:2|max:255',
            'description'    => 'nullable|string|max:1000',
            'goal_id'        => 'nullable|exists:goals,goal_id',
            'priority'       => 'required|in:low,medium,high',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'due_date'       => 'nullable|date',
            'remind_at'      => 'nullable|date',
            'repeat_days'    => 'nullable|array',
            'repeat_days.*'  => 'in:mon,tue,wed,thu,fri,sat,sun',
        ], [
            'title.required'    => 'Task name is required.',
            'title.min'         => 'Task name must be at least 2 characters.',
            'priority.required' => 'Please select a priority.',
        ]);

        $task =$this->taskService->create(auth()->user()->user_id, $validated);

        // Dispatch reminder job if remind_at is set
        if (!empty($validated['remind_at'])) {
            \App\Jobs\SendTaskReminderJob::dispatch(
                auth()->user(),
                $task
            )->delay(\Carbon\Carbon::parse($validated['remind_at']));
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(int $id)
    {
        $task = $this->taskService->findById($id);
        $user = auth()->user();
        if(!$user){
             abort(401);
        }

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $timeLogs    = $this->taskService->getTimeLogs($task);
        $isRunning   = $task->is_running;
        $activeLog   = $task->timeLogs()->whereNull('end_time')->first();

        return view('tasks.show', compact('task', 'timeLogs', 'isRunning', 'activeLog'));
    }

    public function edit(int $id)
    {
        $task = $this->taskService->findById($id);

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $goals = $this->goalService->getActive(auth()->user()->user_id);

        return view('tasks.edit', compact('task', 'goals'));
    }

    public function update(Request $request, int $id)
    {
        $task = $this->taskService->findById($id);

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $validated = $request->validate([
            'title'          => 'required|string|min:2|max:255',
            'description'    => 'nullable|string|max:1000',
            'goal_id'        => 'nullable|exists:goals,goal_id',
            'priority'       => 'required|in:low,medium,high',
            'status'         => 'sometimes|in:todo,in_progress,done,cancelled',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'due_date'       => 'nullable|date',
            'remind_at'      => 'nullable|date',
            'repeat_days'    => 'nullable|array',
            'repeat_days.*'  => 'in:mon,tue,wed,thu,fri,sat,sun',
        ], [
            'title.required'    => 'Task name is required.',
            'title.min'         => 'Task name must be at least 2 characters.',
            'priority.required' => 'Please select a priority.',
        ]);

        $this->taskService->update($task, $validated);

        return redirect()->route('tasks.show', $id)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(int $id)
    {
        $task = $this->taskService->findById($id);

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $this->taskService->delete($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted.');
    }

    // ===== Timer =====

    public function startTimer(int $id)
    {
        $task = $this->taskService->findById($id);

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $this->taskService->startTimer($task);

        return redirect()->route('tasks.show', $id)
            ->with('success', 'Timer started.');
    }

    public function stopTimer(int $id)
    {
        $task = $this->taskService->findById($id);

        if (!$task || !$this->taskService->authorizeUser($task, auth()->user()->user_id)) {
            abort(403);
        }

        $this->taskService->stopTimer($task);

        return redirect()->route('tasks.show', $id)
            ->with('success', 'Timer stopped.');
    }
}