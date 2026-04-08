<?php

namespace App\Services;

use App\Models\Goal;
use App\Repositories\GoalRepository;
use Illuminate\Database\Eloquent\Collection;

class GoalService
{
    public function __construct(
        protected GoalRepository $goalRepository
    ){}

    public function getAllForUser(int $userId)
    {
        return $this->goalRepository->getAllForUser($userId);
    }

    public function findById(int $goalId): ?Goal
    {
        return $this->goalRepository->findById($goalId);
    }

    public function create(int $userId , array $data): Goal
    {
        return $this->goalRepository->create([
            'user_id'   => $userId,
            'title'     => $data['title'],
            'description' => $data['description'] ?? null,
            'type'        => $data['type'],
            'priority'    => $data['priority'],
            'status'      => 'active',
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,

        ]);
    }

    public function update(Goal $goal, array $data): Goal{

            return $this->goalRepository->update($goal,[
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'type'        => $data['type'],
                'priority'   => $data['priority'],
                'status'     => $data['status'],
                'start_date'   => $data['start_date'] ?? null,
                'end_date'     => $data['end_date'] ?? null,

            ]);

            
    }

    public function delete(Goal $goal): void
    {
        $this->goalRepository->delete($goal);
    }


    public function getByType(int $userId , string $type): Collection
    {
        return $this->goalRepository->getByType($userId , $type);
    }

    public function getActive(int $userId)
    {
        return $this->goalRepository->getActive($userId);
    }

    public function authorizeUser(Goal $goal , int $userId): bool
    {
        return $goal->user_id === $userId;
    }






}