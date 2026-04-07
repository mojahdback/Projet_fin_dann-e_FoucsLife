<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    public function showForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->authService->login(
                $validated['email'],
                $validated['password']
        );

        if(!$user){
            return back()->withErrors([
                'email' => 'Your email or password wrong'
            ]);
        }

        session()->regenerate();
        session(['auth_user_id' => $user->user_id]);


        return  $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
    }

    public function logout(Request $request){
        session()->forget('auth_user_id');
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    
}