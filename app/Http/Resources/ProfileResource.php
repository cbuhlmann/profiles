<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray(Request $request)
    {
        $data = parent::toArray($request);

        if (! auth('sanctum')->check()) {
            unset($data['status']);
        }

        return $data;
    }
}
