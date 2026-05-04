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
        'due_date',
        'scheduled_date',
        'scheduled_time',
        'remind_at',
        'reminder_sent',
        'repeat_days',
    ];

    protected $casts = [
    'due_date'       => 'date',
    'scheduled_date' => 'date',
    'remind_at'      => 'datetime',
    'repeat_days'    => 'array',
    'reminder_sent'  => 'boolean',
];

    // ===== Relations =====

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

    // ===== Accessors =====

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
        return $this->scheduled_date
            && $this->scheduled_date->isPast()
            && !$this->scheduled_date->isToday()
            && $this->status !== 'done'
            && $this->status !== 'cancelled';
    }

    public function getIsRunningAttribute(): bool
    {
        return $this->timeLogs()->whereNull('end_time')->exists();
    }

    // ===== Auto-categorization =====

    public function getCategoryAttribute(): string
    {
        if (!$this->scheduled_date) return 'all';

        if ($this->scheduled_date->isToday()) return 'today';

        if ($this->scheduled_date->isCurrentWeek()) return 'week';

        if ($this->scheduled_date->isFuture()) return 'all';

        return 'past'; // missed
    }

    // ===== Scopes =====

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('scheduled_date', today())
                     ->whereNotIn('status', ['cancelled']);
    }

    public function scopeForThisWeek($query)
    {
        return $query->whereBetween('scheduled_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->whereNotIn('status', ['cancelled']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('scheduled_date')
              ->orWhere('scheduled_date', '>=', today());
        })->whereNotIn('status', ['cancelled']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['todo', 'in_progress']);
    }

    public function scopeNotCancelled($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }
}