<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notificationRepository
    ) {}

    // ===== Read =====

    public function getAllForUser(int $userId): Collection
    {
        return $this->notificationRepository->getAllForUser($userId);
    }

    public function getUnread(int $userId): Collection
    {
        return $this->notificationRepository->getUnread($userId);
    }

    public function countUnread(int $userId): int
    {
        return $this->notificationRepository->countUnread($userId);
    }

    public function findById(int $notificationId): ?Notification
    {
        return $this->notificationRepository->findById($notificationId);
    }

    // ===== Actions =====

    public function markAsRead(Notification $notification): void
    {
        $this->notificationRepository->markAsRead($notification);
    }

    public function markAllAsRead(int $userId): void
    {
        $this->notificationRepository->markAllAsRead($userId);
    }

    public function delete(Notification $notification): void
    {
        $this->notificationRepository->delete($notification);
    }

    public function deleteAllRead(int $userId): void
    {
        $this->notificationRepository->deleteAllRead($userId);
    }

    // ===== Authorization =====

    public function authorizeUser(Notification $notification, int $userId): bool
    {
        return $notification->user_id === $userId;
    }

    // ===== Resolve related link =====

    public function resolveLink(Notification $notification): ?string
    {
        if (!$notification->related_id || !$notification->related_type) {
            return null;
        }

        return match($notification->related_type) {
            'task'  => route('tasks.show',  $notification->related_id),
            'habit' => route('habits.show', $notification->related_id),
            'goal'  => route('goals.show',  $notification->related_id),
            default => null,
        };
    }

    // ===== Notification Creators =====

    public function notifyTaskReminder(
        int    $userId,
        int    $taskId,
        string $taskTitle
    ): Notification {
        return $this->notificationRepository->create([
            'user_id'      => $userId,
            'type'         => 'task_reminder',
            'related_id'   => $taskId,
            'related_type' => 'task',
            'message'      => "Reminder: Task \"{$taskTitle}\" is scheduled soon.",
            'is_read'      => false,
        ]);
    }

    public function notifyHabitAlert(
        int    $userId,
        int    $habitId,
        string $habitName
    ): Notification {
        return $this->notificationRepository->create([
            'user_id'      => $userId,
            'type'         => 'habit_alert',
            'related_id'   => $habitId,
            'related_type' => 'habit',
            'message'      => "Don't forget your habit \"{$habitName}\" today!",
            'is_read'      => false,
        ]);
    }

    public function notifyGoalDeadline(
        int    $userId,
        int    $goalId,
        string $goalTitle
    ): Notification {
        return $this->notificationRepository->create([
            'user_id'      => $userId,
            'type'         => 'goal_deadline',
            'related_id'   => $goalId,
            'related_type' => 'goal',
            'message'      => "Goal \"{$goalTitle}\" deadline is approaching.",
            'is_read'      => false,
        ]);
    }

    public function notifyEvaluationReminder(int $userId): Notification
    {
        return $this->notificationRepository->create([
            'user_id'      => $userId,
            'type'         => 'evaluation_reminder',
            'related_id'   => null,
            'related_type' => null,
            'message'      => 'Time to evaluate your week. How did it go?',
            'is_read'      => false,
        ]);
    }

    public function notifyGeneral(
        int    $userId,
        string $message
    ): Notification {
        return $this->notificationRepository->create([
            'user_id'      => $userId,
            'type'         => 'general',
            'related_id'   => null,
            'related_type' => null,
            'message'      => $message,
            'is_read'      => false,
        ]);
    }
}