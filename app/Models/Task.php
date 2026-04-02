<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'task_id';

    protected $fillable = [
        'user_id',
        'goal_id',
        'title',
        'description',
        'priority',
        'status',
        'period',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'goal_id');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeTracking::class, 'task_id', 'task_id');
    }

   
    public function getTotalMinutesAttribute(): int
    {
        return $this->timeLogs()
            ->whereNotNull('end_time')
            ->get()
            ->sum(fn($log) => Carbon::parse($log->start_time)
                ->diffInMinutes(Carbon::parse($log->end_time)));
    }

 
    public function getFormattedTimeAttribute(): string
    {
        $minutes = $this->total_minutes;
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h}h {$m}min" : "{$m}min";
    }

  
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== 'done';
    }

  
    public function getIsRunningAttribute(): bool
    {
        return $this->timeLogs()->whereNull('end_time')->exists();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForToday($query)
    {
        return $query->where('period', 'day')
                     ->whereDate('due_date', today());
    }

    public function scopeByPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['todo', 'in_progress']);
    }
}
