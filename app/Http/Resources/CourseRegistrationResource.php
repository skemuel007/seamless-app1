<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseRegistrationResource extends JsonResource
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
            'course_name' => $this->courseName,
            'course_code' => $this->courseCode,
            'unit' => $this->unit,
            'text' => $this->text,
            'users' => UserRegistrationResource::collection($this->users)
        ];
    }
}
