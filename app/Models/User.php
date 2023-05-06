<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia ;


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'facebook_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function points()
    {
        return $this->hasMany(Point::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function registerUser($request)
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
            // event(new Registered($user));
            $profile = Profile::create([
                'firstname' => $validateData['firstname'],
                'lastname' => $validateData['lastname'],
                'fullname' => $validateData['firstname'] . ' ' . $validateData['lastname'],
                'user_id' => $user->id
            ]);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}
