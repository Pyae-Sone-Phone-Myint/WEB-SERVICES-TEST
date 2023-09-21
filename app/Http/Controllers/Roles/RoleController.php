<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleDetailResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-or-manager', User::class);
        $roles = Role::get();
        return RoleDetailResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|min:3|unique:roles,name",
            "label" => "required",
            "permission_id" => "required"
        ]);
        try {
            DB::beginTransaction();

            $this->authorize('admin-or-manager', User::class);
            $role = new Role();
            $role->name = $request->name;
            $role->label = $request->label;
            $role->save();

            $role->permissions()->attach($request->permission_id);
            DB::commit();
            return response()->json([
                "message" => $role->label . " created successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|min:3",
            "label" => "required",
            "permission_id" => "required"
        ]);
        try {
            DB::beginTransaction();

            $this->authorize('admin-only', User::class);
            $role = Role::find($id);

            if (is_null($role)) {
                return response()->json([
                    "message" => "Not Found"
                ], 404);
            }
            $role->name = $request->name;
            $role->label = $request->label;
            $role->update();

            $role->permissions()->sync($request->permission_id);
            DB::commit();
            return response()->json([
                "message" => $role->label . " updated successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        $this->authorize('admin-only', User::class);
        if (is_null($role)) {
            return response()->json([
                "message" => "Not Found"
            ], 404);
        }
        $role->delete();
        return response()->json([
            "message" => "Deleted Successfully"
        ]);
    }
}
