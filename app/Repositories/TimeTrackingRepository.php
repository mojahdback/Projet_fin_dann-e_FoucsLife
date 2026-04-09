<?php

namespace App\Repositories;

use App\Models\TimeTracking;

class TimeTrackingRepository
{
    public function getRunningForTask(int $taskId): ?TimeTracking
    {
        return TimeTracking::running()
            ->where('task_id' , $taskId)
            ->first();
    }

    public function start(int $taskId): TimeTracking
    {
        return TimeTracking::create([
            'task_id'  => $taskId,
            'start_time' => now(),
            'end_time' => null ,
        ]);
    }


    public function stop(TimeTracking $log): TimeTracking
    {
        $log->update(['end_time' => now()]);
        return $log->fresh();
    }

    public function getForTask(int $taskId)
    {
        return TimeTracking::where('task_id' , $taskId)
            ->completed()
            ->orderBy('start_time', 'desc')
            ->get();
    }

    
}












