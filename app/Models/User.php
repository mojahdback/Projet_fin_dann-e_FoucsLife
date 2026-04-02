<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

     public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'user_id' , 'user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class , 'user_id' , 'user_id');
    }

    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class, 'user_id' , 'user_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(SelfEvaluation::class , 'user_id' , 'user_id');

    }

    public function notifications(): HasMany 
    {
        return $this->hasMany(Notification::class , 'user_id' , 'user_id');

    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeAdmins($query)
    {
        return $query->where('role' , 'admin');
    }
}
