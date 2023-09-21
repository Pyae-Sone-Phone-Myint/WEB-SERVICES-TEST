<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleDetailResource;
use App\Http\Resources\UserDetailResource;
use App\Models\Department;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use function PHPSTORM_META\map;

class UserController extends Controller
{

    public function userProfile(Request $request)
    {
        $user = Auth::user();
        if ($request->has('code')) {
            Gate::authorize('admin-or-manager', User::class); // Only Manager or Admin can search by code
            $code = $request->code;
            $user = User::whereHas('staff', function ($query) use ($code) {
                $query->where('code', $code);
            })->first();
        }
        $staffCount = "No staff";
        if ($this->isManager()) {
            $department = Auth::user()->staff->department->label;
            $managerDepartment = Auth::user()->staff->department_id;
            $staff = Staff::where("department_id", $managerDepartment)->count('id');
            $staffCount = "There are " . $staff . " staffs in this " . $department . ".";
        }
        if ($this->isAdmin()) {
            $staff = Staff::count('id');
            $staffCount = "There are " . $staff . " staffs.";
        }
        return response()->json([
            "data" => new UserDetailResource($user),
            "staff_count" => $staffCount,
        ]);
    }

    public function checkPosition($position)
    {
        $user = Auth::user();
        return $user->roles->contains('name', $position);
    }

    public function isManager()
    {
        return $this->checkPosition('manager');
    }

    public function isAdmin()
    {
        return $this->checkPosition('admin');
    }

    public function staffs()
    {
        if ($this->isAdmin()) {
            $user = User::where('id', '!=', Auth::id())->latest('id')->paginate(10)
                ->withQueryString();
            return response()->json([
                "data" => UserDetailResource::collection($user)->resource,
            ]);
        }
        if ($this->isManager()) {
            $managerDepartment = Auth::user()->staff->department_id;
            $department  = Department::find($managerDepartment);
            $user = $department->users->where('id', '!=', Auth::id());
            return response()->json([
                "data" => UserDetailResource::collection($user),

            ]);
        }

        return response()->json([
            "error" => "you are not allowed"
        ], 403);
    }
}
