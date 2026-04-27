<?php
// app/Repositories/NotificationRepository.php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    public function getAllForUser(int $userId): Collection
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnread(int $userId): Collection
    {
        return Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function countUnread(int $userId): int
    {
        return Notification::forUser($userId)
            ->unread()
            ->count();
    }

    public function findById(int $notificationId): ?Notification
    {
        return Notification::find($notificationId);
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function markAsRead(Notification $notification): void
    {
        $notification->update(['is_read' => true]);
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)
            ->unread()
            ->update(['is_read' => true]);
    }

    public function delete(Notification $notification): void
    {
        $notification->delete();
    }

    public function deleteAllRead(int $userId): void
    {
        Notification::forUser($userId)
            ->where('is_read', true)
            ->delete();
    }
}