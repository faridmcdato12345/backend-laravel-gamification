<?php

namespace App\Http\Controllers\Api\SocialAuth;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        $auth = new AuthController;
        try {
            $user = Socialite::driver('facebook')->user();
            $findUser = User::where('facebook_id',$user->id)->first();
            if(!$findUser){
                $theUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'facebook_id'=> $user->id,
                        'password' => Hash::make('password')
                    ]
                );   
            }else{
                $theUser = [
                    'email' => $findUser->email,
                    'password' => $findUser->password
                ];
            }
            return $theUser;
            return $auth->login($theUser);
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
