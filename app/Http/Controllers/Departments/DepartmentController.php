<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentDetailResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::get();
        return DepartmentDetailResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { {
            $request->validate([
                "name" => "required|min:3|unique:departments,name",
                "label" => "required",
            ]);
            try {
                DB::beginTransaction();

                $this->authorize('admin-or-manager', User::class);
                $department = new Department();
                $department->name = $request->name;
                $department->label = $request->label;
                $department->save();

                DB::commit();
                return response()->json([
                    "message" => $department->label . " created successfully"
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
            }
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
    { {
            $request->validate([
                "name" => "required|min:3",
                "label" => "required",
            ]);
            try {
                DB::beginTransaction();

                $this->authorize('admin-or-manager', User::class);
                $department = Department::find($id);
                if (is_null($department)) {
                    return response()->json([
                        "message" => "Not Found"
                    ], 404);
                }
                $department->name = $request->name;
                $department->label = $request->label;
                $department->update();

                DB::commit();
                return response()->json([
                    "message" => $department->label . " updated successfully"
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('admin-only', User::class);
        $department = Department::find($id);
        if (is_null($department)) {
            return response()->json([
                "message" => "Not Found"
            ], 404);
        }
        $department->delete();
        return response()->json([
            "message" => "deleted successfully"
        ]);
    }
}
