<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Services\GoalService;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function __construct(
        protected GoalService $goalService
    ){}

    public function index()
    {
        $userId = session('auth_user_id');
        $goals  = $this->goalService->getAllForUser($userId);

        return view('goals.index' , compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'description'  => 'nullable|string',
            'type'        => 'required|in:short_term,mid_term,long_term',
            'priority'    => 'required|in:low,medium,high',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',

 
        ]);

        $this->goalService->create(session('auth_user_id'), $validated);

        return redirect()->route('goals.index')
                ->with('success' , 'Goal created successfully.');
    }

    public function show(int $id)
    {
        $goal = $this->goalService->findById($id);

        if(!$goal || !$this->goalService->authorizeUser($goal , session('auth_user_id'))){
            abort(403);
        }

        return view('goals.show' , compact('goal'));
    }


    public function edit(int $id)
    {
        $goal = $this->goalService->findById($id);

        if(!$goal || !$this->goalService->authorizeUser($goal, session('auth_user_id'))){
            abort(403);
        }

        return view('goals.edit' , compact('goal'));
    }

    public function update(Request $request, int $id){

        $goal = $this->goalService->findById($id);

        if(!$goal || !$this->goalService->authorizeUser($goal , session('auth_user_id'))){
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'  => 'required|in:short_term,mid_term,long_term',
            'priority' => 'required|in:low,medium,high',
            'status'  => 'required|in:active,paused,done,cancelled',
            'start_date'  => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $this->goalService->update($goal, $validated);

        return redirect()->route('goals.index')
            ->with('success' , 'Goal updated successfully.');
    }


    public function destroy(int $id)
    {
        $goal = $this->goalService->findById($id);

        if(!$goal || !$this->goalService->authorizeUser($goal, session('auth_user_id'))){
            abort(403);
        }

        $this->goalService->delete($goal);

        return redirect()->route('goals.index')
            ->with('success' , 'Goal deleted successfully.');
    }




}