<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskReminderJob;
use App\Jobs\SendHabitReminderJob;
use App\Models\Task;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Console\Command;

class SendScheduledReminders extends Command
{
    protected $signature   = 'reminders:send';
    protected $description = 'Send scheduled task and habit reminders';

    public function handle(): void
    {
        $this->sendTaskReminders();
        $this->sendHabitReminders();
        $this->info('Reminders sent.');
    }

    private function sendTaskReminders(): void
    {
        Task::whereNotNull('remind_at')
            ->where('reminder_sent', false)
            ->where('remind_at', '<=', now())
            ->whereNotIn('status', ['done', 'cancelled'])
            ->with('user')
            ->get()
            ->each(function (Task $task) {
                SendTaskReminderJob::dispatch($task->user, $task);
            });
    }

   private function sendHabitReminders(): void
{
    Habit::whereNotNull('remind_at')
        ->where('is_active', true)
        ->where('reminder_sent_today', false)
        ->where('remind_at', '<=', now())
        ->with('user')
        ->get()
        ->each(function (Habit $habit) {
            SendHabitReminderJob::dispatch($habit->user, $habit);
        });
}

}