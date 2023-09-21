<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "label" => $this->label,
            "flag" => $this->flag,
            "total_staff" => $this->staffs->count('id'),
            "staffs" => StaffResource::collection($this->staffs)
        ];
    }
}
