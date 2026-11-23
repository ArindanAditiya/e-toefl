<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = auth()->user();

        // Update login activity
        if ($user) {
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);
        }

        // Arahkan administrator ke dashboard admin
        if ($user && $user->level == 1) {
            return redirect()->intended('/admin/dashboard');
        }

        // Arahkan peserta ke dashboard user
        return redirect()->intended('/user/dashboard');
    }
}
