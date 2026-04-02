<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitTracking extends Model
{
    use HasFactory;

    protected $primaryKey = 'tracking_id';

    protected $fillable = [
        'habit_id',
        'date',
        'completed',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'completed' => 'boolean',
        ];
    }
    
    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class, 'habit_id', 'habit_id');
    }


    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }
}
