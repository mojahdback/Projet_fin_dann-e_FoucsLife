<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Habit extends Model
{
    use HasFactory;

    protected $primaryKey = 'habit_id';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'frequency',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(HabitTracking::class, 'habit_id', 'habit_id');
    }

   
    public function todayTracking(): HasOne
    {
        return $this->hasOne(HabitTracking::class, 'habit_id', 'habit_id')
                    ->whereDate('date', today());
    }

 
    public function getIsDoneTodayAttribute(): bool
    {
        return $this->todayTracking?->completed ?? false;
    }

  
    public function getStreakAttribute(): int
    {
        $logs = $this->trackings()
            ->where('completed', true)
            ->orderByDesc('date')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d));

        if ($logs->isEmpty()) return 0;

        $streak = 0;
        $expected = Carbon::today();

        foreach ($logs as $date) {
            if ($date->isSameDay($expected)) {
                $streak++;
                $expected = $expected->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

   
    public function getCompletionRateAttribute(): int
    {
        $total = $this->trackings()
            ->where('date', '>=', now()->subDays(30))
            ->count();

        if ($total === 0) return 0;

        $done = $this->trackings()
            ->where('date', '>=', now()->subDays(30))
            ->where('completed', true)
            ->count();

        return (int) round(($done / $total) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
