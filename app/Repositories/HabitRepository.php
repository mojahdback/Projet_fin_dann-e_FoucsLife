<?php

namespace App\Repositories;
use App\Models\HabitTracking;
use App\Models\Habit;
use Illuminate\Database\Eloquent\Collection;

class HabitRepository
{
    public function getAllForUser(int $userId)
    {
        return Habit::forUser($userId)
            ->with('todayTracking')
            ->orderBy('created_at' , 'desc')
            ->get();
    }

    public function findById(int $habitId)
    {
        return Habit::with(['trackings' , 'todayTracking'])->find($habitId);
    }

    public function create(array $data)
    {
        return Habit::create($data);
    }

    public function update(Habit $habit , array $data)
    {
        $habit->update($data);
        return $habit->fresh();
    }

    public function delete(Habit $habit)
    {
        $habit->delete();
    }

    public function getActive(int $userId)
    {
        return Habit::forUser($userId)
            ->active()
            ->with('todayTracking')
            ->get();
    }

    public function getTodayTracking(int $habitId): ?HabitTracking
    {
        return HabitTracking::where('habit_id' , $habitId)
            ->forDate(today())
            ->first();
    }

    public function saveTodayTracking(int $habitId, bool $isCompleted, ?string $note ): HabitTracking
    {
             return HabitTracking::updateOrCreate(
            [
                'habit_id' => $habitId,
                'date'     => today(),
            ],
            [
                'completed' => $isCompleted,
                'note'      => $note,
            ]
        );
       
    }


}