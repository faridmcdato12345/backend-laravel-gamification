<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function fetchUserPointPerGame()
    {
        return $this->with('users')->get();
    }

    public function fetchUserTotalPoints()
    {
        return $this->with('users')->sum('points');
    }
}
