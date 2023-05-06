<?php

namespace App\Models;

use App\Models\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profile extends Model implements HasMedia
{
    public $user;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'firstname',
        'lastname',
        'user_id',
        'fullname',
        'file_name'
    ];
    private function setUser()
    {
        $this->user = auth()->user();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function fetchUser($id)
    {
        return $this->with('user')->where('id',$id)->get();
    }

    public function fetchUserTotalPoints()
    {
        return Point::where('user_id',auth()->user()->id)->sum('points');
    }

    public function updateProfile($id, $request)
    {
        return $this->where('user_id',$id)->update($request);
    }
}
