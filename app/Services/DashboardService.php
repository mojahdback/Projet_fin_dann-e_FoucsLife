<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\Task;
use App\Models\Habit;

use App\Models\TimeTracking;


class DashboardService
{
    public function getData(int $userId): array
    {
            return[
                'goals' => $this->goalsStats($userId),
                'tasks'  => $this->tasksStats($userId),
                'habits' => $this->habitsStats($userId),
                'time' => $this->timeStats($userId),
           ];
    }

    private function goalsStats(int $userId): array
    {
        $all = Goal::forUser($userId)->with('tasks')->get();

        return [

        'total'  => $all->count(),
        'active' => $all->where('status' , 'active')->count(),
        'done'   => $all->where('status' , 'done')->count(),
        'overdue' => $all->filter(fn($g) => $g->is_overdue)->count(),
        'recent'  => $all->sortByDesc('created_at')->take(3)->values(),

        ];
    }

    private function tasksStats(int $userId): array
    {
        $all = Task::forUser($userId)->get();
        $today = Task::forUser($userId)->forToday()->get();

        return [
            'total' => $all->count(),
            'done'  => $all->where('status', 'done')->count(),
            'in_progress' => $all->where('status' , 'in_progress')->count(),
            'overdue'  => $all->filter(fn($t) => $t->is_overdue)->count(),
            'today'  => $today,
            'today_done' => $today->where('status' , 'done')->count(),
        ];
    }

    private function habitsStats(int $userId): array
    {
        $habits = Habit::forUser($userId)
            ->active()
            ->with('todayTracking')
            ->get();
        
        return [
            'total' => $habits->count(),
            'done_today' => $habits->filter(fn($h) => $h->is_done_today)->count(),
            'pending_today' => $habits->filter(fn($h) => !$h-> is_done_today)->count(),
            'habits'  => $habits,

        ];
    }

    private function timeStats(int $userId): array
    {
        $taskIds = Task::forUser($userId)->pluck('task_id');

        $todayMinutes = TimeTracking::whereIn('task_id', $taskIds)
            ->today()
            ->completed()
            ->get()
            ->sum(fn($log) => $log->duration_in_minutes);

        $weekMinutes = TimeTracking::whereIn('task_id' , $taskIds)
            ->completed()
            ->whereBetween('start_time' , [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->sum(fn($log) => $log->duration_in_minutes);
        
        return [
            'today_minutes' => $todayMinutes,
            'today_formatted' => $this->formatMinutes($todayMinutes),
            'week_minutes' => $weekMinutes,
            'week_formatted' => $this->formatMinutes($weekMinutes),

        ];
    }


    private function formatMinutes(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60 ;
        return $h > 0 ? "{$h}h {$m}min" : "{$m}min";
    }
}