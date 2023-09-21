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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

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

    public function randomString()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function createStaff(Request $request)
    {
        $request->validate([
            "name" => "required|min:3",
            "email" => "email|required|unique:users,email",
            "password" => "required|confirmed|min:6",
            "mobile" => "required|min:9",
            "joinedDate" => "required",
            "depId" => "required",
            "position" => "required",
            "age" => "required",
            "gender" => "required",
            "role_id" => "array|required"
        ]);
        try {
            DB::beginTransaction();
            $this->authorize('admin-only', User::class);
            $authUser = Auth::user()->staff->position;
            $name = $request->name;
            $email = $request->email;
            $staff = new Staff();
            $staff->code = $this->randomString();
            $staff->name = $name;
            $staff->email = $email;
            $staff->mobile = $request->mobile;
            $staff->joinedDate = $request->joinedDate;
            $staff->department_id = $request->depId;
            $staff->position = $request->position;
            $staff->age = $request->age;
            $staff->gender = $request->gender;
            $staff->status = "active";
            $staff->createdBy = $authUser;
            $staff->updatedBy = $authUser;
            $staff->save(); // use database

            $user = new User();
            $user->staff_id = $staff->id;
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($request->password);
            $user->createdBy = $authUser;
            $user->updatedBy = $authUser;
            $user->save(); // use database

            $user->roles()->attach($request->role_id); // use database

            DB::commit();
            return response()->json([
                "message" => $staff->name . " has been created successfully.",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
        }
    }

    public function editStaff(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|min:3",
            "mobile" => "required|min:9",
            "depId" => "required",
            "position" => "required",
            "age" => "required",
            "role_id" => "array|required"
        ]);
        try {
            DB::beginTransaction();
            $staff = Staff::find($id);
            $this->authorize('admin-or-manager', User::class);
            if (is_null($staff)) {
                return response()->json([
                    "message" => "Staff not found"
                ], 404);
            }
            $name = $request->name;
            $staff->name = $name;
            $staff->mobile = $request->mobile;
            $staff->department_id = $request->depId;
            $staff->position = $request->position;
            $staff->age = $request->age;
            $staff->update(); // use database

            $user = $staff->user;
            $user->name = $name;
            $user->update(); // use database

            $user->roles()->sync($request->role_id); // use database

            DB::commit();
            return response()->json([
                "message" => $staff->name . " updated successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $staff = Staff::find($id);
        $this->authorize('admin-only', User::class);
        if (is_null($staff)) {
            return response()->json([
                "message" => "Staff not found"
            ], 404);
        }
        $staff->delete();
        return response()->json([
            "message" => "Staff deleted successfully"
        ]);
    }
}
