<?php

namespace App\Http\Middleware;

use App\Models\User;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request , Closure $next){
        $userId = session('auth_user_id');
        $user = User::find($userId);

        if(!$user || !$user->isAdmin()){
            abort(403 , 'Admin only you can enter');
        }

        return $next($request);
    }
}