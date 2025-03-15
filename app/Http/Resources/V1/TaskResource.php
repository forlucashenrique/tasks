<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = new UserResource($this->user);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'owner' => $user->name,
            'finished' => !!$this->finished,
            'created_date' => $this->created_at_formatted,
            'finished_date_limit' => $this->finished_date_limit,
            'time_to_finished' => $this->time_to_finish > 0 ? $this->time_to_finish : null,
            'status' => $this->status,
        ];
    }
}
