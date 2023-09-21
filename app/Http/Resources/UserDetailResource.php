<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $staff = $this->staff;
        return [
            "user_info" => [
                "code" => $staff->code,
                "name" => $staff->name,
                "email" => $staff->email,
                "mobile" => $staff->mobile,
                "joinedDate" => $staff->joinedDate,
                "department" => new DepartmentResource($staff->department),
                "position" => $staff->position,
                "age" => $staff->age,
                "gender" => $staff->gender,
                "status" => $staff->status,
                "createdBy" => $staff->createdBy,
                "updatedBy" => $staff->updatedBy,
            ],
            "roles" => RoleDetailResource::collection($this->roles),
        ];
    }
}
