<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitTracking;
use App\Repositories\HabitRepository;
use Illuminate\Database\Eloquent\Collection;

class HabitService
{
    public function __construct(
        protected HabitRepository $habitRepository
    ){}

    public function getAllForUser(int $userId)
    {
        return $this->habitRepository->getAllForUser($userId);
    }

    public function findById(int $habitId): ?Habit
    {
        return $this->habitRepository->findById($habitId);

    }

    public function create(int $userId , array $data): Habit
    {
        return $this->habitRepository->create([
            'user_id'     => $userId,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'frequency'   => $data['frequency'],
            'is_active'   => true,

        ]);
    }

    public function update(Habit $habit , array $data)
    {
        return $this->habitRepository->update($habit ,[
             'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'frequency'   => $data['frequency'],
            'is_active'   => $data['is_active'] ?? $habit->is_active,

        ]);
    }

    public function delete(Habit $habit): void
    {
        $this->habitRepository->delete($habit);
    }

    public function getActive(int $userId)
    {
        return $this->habitRepository->getActive($userId);
    }

    public function authorizeUser(Habit $habit , int $userId): bool
    {
        return $habit->user_id === $userId;
    }

    public function updateTodayStatus(Habit $habit , ?string $note = null): HabitTracking
    {
        $tracking = $this->habitRepository->getTodayTracking($habit->habit_id);
        $completed = $tracking ? !$tracking->completed : true;

        return $this->habitRepository->saveTodayTracking(
                $habit->habit_id,
                $completed,
                $note

        );
    }
    public function markToday(Habit $habit, bool $completed, ?string $note = null): HabitTracking
    {
        return $this->habitRepository->saveTodayTracking(
            $habit->habit_id,
            $completed,
            $note
        );
    }












}