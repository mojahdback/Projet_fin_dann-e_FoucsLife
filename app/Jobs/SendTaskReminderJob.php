<?php

namespace App\Jobs;

use App\Mail\TaskReminderMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Task $task
    ) {}

    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new TaskReminderMail($this->user, $this->task));

        $service = app(\App\Services\NotificationService::class);
        $service->notifyTaskReminder(
        $this->user->user_id,
        $this->task->task_id,
        $this->task->title
        );

        $this->task->update(['reminder_sent' => true]);
    }
}