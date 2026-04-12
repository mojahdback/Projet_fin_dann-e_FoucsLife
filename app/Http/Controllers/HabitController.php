<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Services\HabitService;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function __construct(
        protected HabitService $habitService
    ) {}

    public function index()
    {
        $userId = session('auth_user_id');
        $habits = $this->habitService->getAllForUser($userId);

        return view('habits.index', compact('habits'));
    }

    public function create()
    {
        return view('habits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency'   => 'required|in:daily,weekly,monthly',
        ]);

        $this->habitService->create(session('auth_user_id'), $validated);

        return redirect()->route('habits.index')
            ->with('success', 'Habit created successfully.');
    }

    public function show(int $id)
    {
        $habit = $this->habitService->findById($id);

        if (!$habit || !$this->habitService->authorizeUser($habit, session('auth_user_id'))) {
            abort(403);
        }

        return view('habits.show', compact('habit'));
    }

    public function edit(int $id)
    {
        $habit = $this->habitService->findById($id);

        if (!$habit || !$this->habitService->authorizeUser($habit, session('auth_user_id'))) {
            abort(403);
        }

        return view('habits.edit', compact('habit'));
    }

    public function update(Request $request, int $id)
    {
        $habit = $this->habitService->findById($id);

        if (!$habit || !$this->habitService->authorizeUser($habit, session('auth_user_id'))) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency'   => 'required|in:daily,weekly,monthly',
            'is_active'   => 'boolean',
        ]);

        $this->habitService->update($habit, $validated);

        return redirect()->route('habits.index')
            ->with('success', 'Habit updated successfully.');
    }

    public function destroy(int $id)
    {
        $habit = $this->habitService->findById($id);

        if (!$habit || !$this->habitService->authorizeUser($habit, session('auth_user_id'))) {
            abort(403);
        }

        $this->habitService->delete($habit);

        return redirect()->route('habits.index')
            ->with('success', 'Habit deleted successfully.');
    }

    
    public function switchToday(Request $request, int $id)
    {
        $habit = $this->habitService->findById($id);

        if (!$habit || !$this->habitService->authorizeUser($habit, session('auth_user_id'))) {
            abort(403);
        }

        $note = $request->input('note');
        $this->habitService->updateTodayStatus($habit, $note);

        return redirect()->route('habits.index')
            ->with('success', 'Habit updated for today.');
    }
}