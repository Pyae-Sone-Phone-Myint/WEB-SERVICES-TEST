<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-or-manager', User::class);
        $permissions = Permission::get();
        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:permissions,name",
            "label" => "required",
            "role_id" => "required"
        ]);
        try {
            DB::beginTransaction();

            $this->authorize('admin-or-manager', User::class);
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->label = $request->label;
            $permission->save();

            $permission->roles()->attach($request->role_id);

            DB::commit();
            return response()->json([
                "message" => "created successfully"
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
            "name" => "required",
            "label" => "required",
            "role_id" => "required"
        ]);
        try {
            DB::beginTransaction();

            $this->authorize('admin-or-manager', User::class);
            $permission = Permission::find($id);
            if (is_null($permission)) {
                return response()->json([
                    "message" => "Not Found"
                ], 404);
            }
            $permission->name = $request->name;
            $permission->label = $request->label;
            $permission->update();

            $permission->roles()->sync($request->role_id);

            DB::commit();
            return response()->json([
                "message" => "updated successfully"
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
        $this->authorize('admin-only', User::class);
        $permission = Permission::find($id);
        if (is_null($permission)) {
            return response()->json([
                "message" => "Not Found"
            ], 404);
        }
        $permission->delete();

        return response()->json([
            "message" => "deleted successfully"
        ]);
    }
}
