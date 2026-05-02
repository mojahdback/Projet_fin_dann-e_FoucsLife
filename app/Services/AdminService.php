<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    public function __construct(
        protected AdminRepository $adminRepository
    ) {}

    public function getAllUsers(): Collection
    {
        return $this->adminRepository->getAllUsers();
    }

    public function findUserById(int $userId): ?User
    {
        return $this->adminRepository->findUserById($userId);
    }

    public function updateUserRole(User $user, string $role): User
    {
        return $this->adminRepository->updateUserRole($user, $role);
    }

    public function deleteUser(User $user): void
    {
        $this->adminRepository->deleteUser($user);
    }

    public function getDashboardStats(): array
    {
        return [
            'users'          => $this->adminRepository->countUsers(),
            'admins'         => $this->adminRepository->countAdmins(),
            'goals'          => $this->adminRepository->countGoals(),
            'tasks'          => $this->adminRepository->countTasks(),
            'tasks_done'     => $this->adminRepository->countDoneTasks(),
            'habits'         => $this->adminRepository->countHabits(),
            'habits_active'  => $this->adminRepository->countActiveHabits(),
            'evaluations'    => $this->adminRepository->countEvaluations(),
            'recent_users'   => $this->adminRepository->getRecentUsers(),
            'users_per_month'=> $this->adminRepository->getUsersPerMonth(),
        ];
    }

    public function isSelf(User $target, int $currentUserId): bool
    {
        return $target->user_id === $currentUserId;
    }
}