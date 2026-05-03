<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            session()->regenerate();

            $user = Auth::user();

            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Your email or password wrong'
        ]);

    }
    public function logout(Request $request)
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }


}