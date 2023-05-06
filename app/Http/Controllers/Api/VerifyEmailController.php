<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));
        if($user->hasVerifiedEmail()){
            return redirect(env('FRONT_END_URL') . 'user');
        }
        if($user->markEmailAsVerified()){
            event(new Verified($user));
        }
        return redirect(env('FRONT_END_URL') . 'user');
    }
}
