<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'master' => $this->master,
            'slave' => $this->slave,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
