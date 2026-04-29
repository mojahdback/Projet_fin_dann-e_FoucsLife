<?php

namespace App\Mail;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HabitReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User  $user,
        public Habit $habit
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Habit Reminder: {$this->habit->name} — FocusLife",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.habit-reminder',
        );
    }
}