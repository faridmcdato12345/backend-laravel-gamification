<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePointRequest;
use App\Models\Point;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PointController extends Controller
{
    public $userPoint;

    public function __construct(Point $point)
    {
        $this->userPoint = $point;
    }
    public function pointPerGame()
    {
        return $this->userPoint->fetchUserPointPerGame();
    }

    public function totalPoints()
    {
        return $this->userPoint->fetchUserTotalPoints();
    }

    public function store(StorePointRequest $request)
    {
        $user = auth()->user();
        $data = $user->points()->create($request->validated());
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ], Response::HTTP_CREATED);
    }
}
