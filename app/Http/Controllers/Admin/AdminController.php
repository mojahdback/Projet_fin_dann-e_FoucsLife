<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService        $adminService,
        protected NotificationService $notificationService
    ) {}

    // ===== Dashboard =====

    public function dashboard()
    {
        $stats = $this->adminService->getDashboardStats();
        return view('admin.dashboard', compact('stats'));
    }

    // ===== Users =====

    public function users(Request $request)
    {
        $users  = $this->adminService->getAllUsers();
        $search = $request->query('search', '');

        if ($search) {
            $users = $users->filter(fn($u) =>
                str_contains(strtolower($u->name),  strtolower($search)) ||
                str_contains(strtolower($u->email), strtolower($search))
            )->values();
        }

        return view('admin.users.index', compact('users', 'search'));
    }

    public function showUser(int $id)
    {
        $user = $this->adminService->findUserById($id);
        if (!$user) abort(404);

        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, int $id)
    {
        $user = $this->adminService->findUserById($id);
        if (!$user) abort(404);

        if ($this->adminService->isSelf($user, auth()->id())) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $this->adminService->updateUserRole($user, $validated['role']);

        return redirect()->route('admin.users')
            ->with('success', "Role updated for {$user->name}.");
    }

    public function deleteUser(int $id)
    {
        $user = $this->adminService->findUserById($id);
        if (!$user) abort(404);

        if ($this->adminService->isSelf($user, auth()->id())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $this->adminService->deleteUser($user);

        return redirect()->route('admin.users')
            ->with('success', "{$name} has been deleted.");
    }

    public function sendNotification(Request $request, int $id)
    {
        $user = $this->adminService->findUserById($id);
        if (!$user) abort(404);

        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $this->notificationService->notifyGeneral(
            $user->user_id,
            $validated['message']
        );

        return back()->with('success', 'Notification sent successfully.');
    }
}