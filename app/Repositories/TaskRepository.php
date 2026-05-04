<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getAllForUser(int $userId): Collection
    {
        return Task::forUser($userId)
            ->with('goal')
            ->upcoming()
            ->orderBy('scheduled_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $taskId): ?Task
    {
        return Task::with(['goal', 'timeLogs'])->find($taskId);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function getForToday(int $userId): Collection
    {
        return Task::forUser($userId)
            ->forToday()
            ->with('goal')
            ->orderBy('priority', 'desc')
            ->get();
    }

    public function getForThisWeek(int $userId): Collection
    {
        return Task::forUser($userId)
            ->forThisWeek()
            ->with('goal')
            ->orderBy('scheduled_date', 'asc')
            ->get();
    }

    public function getPast(int $userId): Collection
    {
        return Task::forUser($userId)
            ->whereDate('scheduled_date', '<', today())
            ->with('goal')
            ->orderBy('scheduled_date', 'desc')
            ->get();
    }

    public function getByStatus(int $userId, string $status): Collection
    {
        return Task::forUser($userId)
            ->where('status', $status)
            ->with('goal')
            ->orderBy('scheduled_date', 'asc')
            ->get();
    }
}