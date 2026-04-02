<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeTracking extends Model
{
    use HasFactory;

    protected $primaryKey = 'time_id';

    protected $fillable = [
        'task_id',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time'   => 'datetime',
        ];
    }

 
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }


    public function getDurationInMinutesAttribute(): int
    {
        if (!$this->end_time) return 0;
        return (int) Carbon::parse($this->start_time)
            ->diffInMinutes(Carbon::parse($this->end_time));
    }

  
    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->duration_in_minutes;
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h}h {$m}min" : "{$m}min";
    }

   
    public function getIsRunningAttribute(): bool
    {
        return is_null($this->end_time);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    public function scopeRunning($query)
    {
        return $query->whereNull('end_time');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }
}