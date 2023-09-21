<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Staff::class);
    }
}
