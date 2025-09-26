<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Admin, DesignationPermission, Designation, Permission};

class DesignationController extends Controller
{
    public function index() {
        $designations = Designation::where('name','!=','Super Admin')->get();
        return view('admin.designation.index', compact('designations'));
    }

    public function create() {
        return view('admin.designation.create');
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Designation::create([
            'name'             => $request->name,
            'status'           => 1,
        ]);

        return redirect()->route('admin.designation.index')->with('success', 'Designation added successfully');
   
    }

    public function status($id)
    {
        $des = Designation::findOrFail($id);

        $des->status = $des->status ? 0 : 1;
        $des->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    public function delete(Request $request)
    {
        $des = Designation::find($request->id); 
    
        if (!$des) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Designation not found.',
            ]);
        }
    
        $des->delete(); 
        return response()->json([
            'status'    => 200,
            'message'   => 'Designation deleted successfully.',
        ]);
    }

    public function edit($id) {
        $data = Designation::findOrFail($id);
        return view('admin.designation.edit', compact('data'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $des = Designation::findOrFail($request->id);
        $des->update([
            'name'             => $request->name,
        ]);
        return redirect()->route('admin.designation.index')->with('success', 'Designation updated successfully!');
    }

     public function permissions(Request $request, $id) {
        $designation = Designation::findOrFail($id);
        $permissions = Permission::orderBy('parent_name')->whereNotNull('route')->get();
        $assignedPermissions = $designation->permissions->pluck('id')->toArray();

        return view('admin.designation.permission', compact('designation', 'permissions', 'assignedPermissions'));
    }

    public function updatePermissions(Request $request) {
        try{
        $request->validate([
            'designation_id'    => 'required|exists:designations,id',
            'permissions'       => 'array',
            'permissions.*'     => 'exists:permissions,id',
        ]);

        $designation = Designation::findOrFail($request->designation_id);
        $permissionIds = $request->input('permissions', []);

        $designation->permissions()->sync($permissionIds);

        return redirect()->route('admin.designation.list')->with('success', 'Permissions updated successfully.');

        
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update permissions: ' . $e->getMessage());
        }
    }

    public function updatePermissionAjax(Request $request) {
        //dd($request->all());
        try {
        $request->validate([
            'designation_id' => 'required|exists:designations,id',
            'permission_id' => 'required|exists:permissions,id',
            'checked' => 'required|boolean',
        ]);

        $designation = Designation::findOrFail($request->designation_id);
        $permissionId = $request->permission_id;

        if($request->checked) {
            // Add permission if not already attached
            $designation->permissions()->syncWithoutDetaching([$permissionId]);
        } else{
             // Remove permission
             $designation->permissions()->detach([$permissionId]);
        }

        return response()->json(['success' => true]);

    }catch (\Exception $e) {
        //dd($e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
} 
}
