<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;

use App\Services\AuthService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

         $isFirstUser = User::count() === 0;

         $role = ($isFirstUser && $validated['email'] === 'admin@focusLife.com')
                 ? 'admin'
                 : 'user';

         $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $role,
    ]);


        auth()->login($user);
        session()->regenerate();
        
        Mail::to($user->email)->send(
        new \App\Mail\WelcomeMail($user)
    );
        return $user->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('dashboard');

    }
}