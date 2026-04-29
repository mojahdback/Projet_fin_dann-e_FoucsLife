<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Task $task
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reminder: {$this->task->title} — FocusLife",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-reminder',
        );
    }
}