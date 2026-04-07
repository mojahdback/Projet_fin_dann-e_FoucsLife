<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Services\AuthService;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $this->authService->register($validated);

        session()->regenerate();
        session(['auth_user_id' => $user->user_id]);

        return redirect()->route('dashboard');
        
    }
}