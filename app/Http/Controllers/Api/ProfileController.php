<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Point;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\UpdateProfileRequest;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->middleware('api')->except('store');
    }

    public function me()
    {
        $totalPoints = $this->profile->fetchUserTotalPoints();
        $pointsPerGame = Point::select('points')->where('user_id',auth()->user()->id)->get();
        return response()->json([
            'data' => [
                'user' => new UserResource(auth()->user()->load(['profile'])),
                'total_points' => $totalPoints,
                'points_per_game' => $pointsPerGame
            ]
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        if($request->hasFile('file_name')){
            $file = $request->file('file_name');
            $name = 'profile/images/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $data['file_name'] = $name; 
        }
        $user = auth()->user();
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $data);
        return new ProfileResource($profile);
    }
}
