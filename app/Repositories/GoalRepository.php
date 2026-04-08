<?php

namespace App\Repositories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Collection;


class GoalRepository
{
    public function getAllForUser(int $userId): Collection
    {
        return Goal::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $goalId): ?Goal
    {
        return Goal::find($goalId);
    }

    public function create(array $data): Goal
    {
        return Goal::create($data);
    }

    public function update(Goal $goal, array $data): Goal
    {
        $goal->update($data);
        return $goal->fresh();
    }

    public function delete(Goal $goal): void
    {
        $goal->delete();
    }


    public function getByType(int $userId , string $type): Collection
    {
        return Goal::forUser($userId)
            ->byType($type)
            ->orderBy('created_at' , 'desc')
            ->get();
    }

    public function getActive(int $userId): Collection
    {
        return Goal::forUser($userId)
            ->active()
            ->orderBy('end_date', 'asc')
            ->get();
    }
}