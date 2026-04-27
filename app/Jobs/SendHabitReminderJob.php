<?php

namespace App\Jobs;

use App\Mail\HabitReminderMail;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendHabitReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User  $user,
        public Habit $habit
    ) {}

    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new HabitReminderMail($this->user, $this->habit));

        $service = app(\App\Services\NotificationService::class);
        $service->notifyHabitAlert(
        $this->user->user_id,
        $this->habit->habit_id,
        $this->habit->name
    );

        $this->habit->update(['reminder_sent_today' => true]);
    }
}