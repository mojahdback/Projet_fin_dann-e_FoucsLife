<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;


class TaskRepository
{
    public function getAllForUser(int $userId)
    {
        return Task::forUser($userId)
            ->with('goal')
            ->orderBy('created_at' , 'desc')
            ->get();
    }

    public function findById(int $taskId): ?Task
    {
        return Task::with(['goal' , 'timeLogs'])->find($taskId);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task , array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function getByPeriod(int $userId , string $period)
    {
        return Task::forUser($userId)
            ->byPeriod($period)
            ->with('goal')
            ->orderBy('due_date' , 'asc')
            ->get();
    }

    public function getPending(int $userId)
    {
        return Task::forUser($userId)
            ->pending()
            ->with('goal')
            ->orderBy('due_date' , 'asc')
            ->get();
    }


    public function getToday(int $userId)
    {
        return Task::forUser($userId)
            ->forToday()
            ->with('goal')
            ->get();
    }
}