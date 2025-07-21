<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\User;
use Log;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {

        // below is the existing response
        // replace this with your own code
        // the user can be located with Auth facade

        Log::debug('LoginResponse:toResponse - method begins');

        $user = Auth::user();
        if($user->hasRole('sales_man')){
            Log::debug('LoginController:authenticated Salesman Role');
            return redirect('/salesman');
        }
        else {
            return redirect('/');
        }

        return $request->wantsJson()
                    ? response()->json(['two_factor' => false])
                    : redirect()->intended(config('fortify.home'));
    }

}
