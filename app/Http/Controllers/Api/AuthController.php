<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    public $user;
    public function __contruct(User $user)
    {
        $this->user = $user;
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login()
    {
        $credential = request(['email','password']);
        if(!$token = auth()->attempt($credential))
        {
            return response()->json([
                'error' => 'invalid credentials'
            ],Response::HTTP_UNAUTHORIZED);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validateData = $request->validate([
                'firstname' => 'required',
                'lastname' => 'nullable',
                'email' => 'required',
                'password' => 'required'
            ]);
            $user = User::create([
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password'])
            ]);
            event(new Registered($user));
            $profile = Profile::create([
                'firstname' => $validateData['firstname'],
                'lastname' => $validateData['lastname'],
                'fullname' => $validateData['firstname'] . ' ' . $validateData['lastname'],
                'user_id' => $user->id
            ]);
            DB::commit();
            $token = self::login($user);
            return response()->json([
                'status' => 'Success',
                'access_token' => $token
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    public function show(){
        return auth()->user();
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
    
}