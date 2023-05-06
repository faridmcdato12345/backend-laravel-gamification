<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => ucwords($this->firstname),
            'last_name' => ucwords($this->lastname),
            'full_name' => ucwords($this->firstname) . ' ' . ucwords($this->lastname),
            'profile_picture_url' => $this->file_name ? env('APP_URL').'/media'.'/public'.'/'.$this->file_name : '',
            'updated_time' => $this->updated_at->format('Y-m-d')
        ];
    }
}
