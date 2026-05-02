<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Goal;
use App\Models\Task;
use App\Models\Habit;
use App\Models\SelfEvaluation;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository
{

    public function getAllUsers(): Collection
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function findUserById(int $userId): ?User
    {
        return User::with(['goals', 'tasks', 'habits', 'evaluations'])->find($userId);
    }

    public function updateUserRole(User $user, string $role): User
    {
        $user->update(['role' => $role]);
        return $user->fresh();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    // ===== Global Stats =====

    public function countUsers(): int
    {
        return User::count();
    }

    public function countAdmins(): int
    {
        return User::admins()->count();
    }

    public function countGoals(): int
    {
        return Goal::count();
    }

    public function countTasks(): int
    {
        return Task::count();
    }

    public function countHabits(): int
    {
        return Habit::count();
    }

    public function countEvaluations(): int
    {
        return SelfEvaluation::count();
    }

    public function countDoneTasks(): int
    {
        return Task::where('status', 'done')->count();
    }

    public function countActiveHabits(): int
    {
        return Habit::where('is_active', true)->count();
    }

    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUsersPerMonth(): array
    {
        return User::selectRaw(
            'MONTH(created_at) as month, COUNT(*) as count'
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }
}