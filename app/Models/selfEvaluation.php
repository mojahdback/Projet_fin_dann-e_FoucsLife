<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfEvaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'evaluation_id';

    protected $fillable = [
        'user_id',
        'period_type',
        'period_date',
        'score',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'period_date' => 'date',
            'score'       => 'integer',
        ];
    }

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

 
    public function getScoreLabelAttribute(): string
    {
        return match(true) {
            $this->score >= 9 => 'Excellent',
            $this->score >= 7 => 'Bon',
            $this->score >= 5 => 'Moyen',
            $this->score >= 3 => 'Difficile',
            default           => 'Très difficile',
        };
    }

   
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPeriodType($query, string $type)
    {
        return $query->where('period_type', $type);
    }

   
    public function scopeForPeriod($query, string $type, $date)
    {
        return $query->where('period_type', $type)
                     ->whereDate('period_date', $date);
    }
}