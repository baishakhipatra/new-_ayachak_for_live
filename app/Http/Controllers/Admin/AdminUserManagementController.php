<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\{Admin,Designation}; 

class AdminUserManagementController extends Controller
{
    public function index(Request $request) 
    {
        $keyword = $request->input('keyword');

        $query = Admin::query();

        $query->when($keyword, function ($q) use ($keyword) {
            $q->where(function($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        });
        $admins = $query->with('designation')->latest('id')->paginate(10);

        return view('admin.admin-user-management.index', compact('admins'));
    }

    public function create(){
        // return view('admin.admin-user-management.create');
        $designations = Designation::where('status', 1)->get();
        return view('admin.admin-user-management.create', compact('designations'));
    }

    public function store(Request $request) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:admins,email',
            'phone'            => 'required|digits:10',
            'password'         => 'required|string|min:6',
            'designation_id' => 'required|exists:designations,id',
        ]);

        //If validation fails
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Admin::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'password'         => Hash::make($request->password),
            'status'           => 1,
            'designation_id' => $request->designation_id,
        ]);
        //dd('Hi');

        return redirect()->route('admin.admin-user-management.index')->with('success', 'Admin created successfully');
    }

    public function edit($id) {
        $data = Admin::findOrFail($id);
        $designations = Designation::where('status', 1)->get();
        return view('admin.admin-user-management.edit', compact('data', 'designations'));
    }

    public function update(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email,' . $request->id,
            'phone'     => 'required|digits:10',
            'designation_id' => 'required|exists:designations,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = Admin::findOrFail($request->id);
        $admin->update([
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'designation_id' => $request->designation_id,
        ]);
        return redirect()->route('admin.admin-user-management.index')->with('success', 'Admin updated successfully!');
    }

    public function status($id)
    {
        $user = Admin::findOrFail($id);

        $user->status = $user->status ? 0 : 1;
        $user->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    public function delete(Request $request){
        $user = Admin::find($request->id); 
    
        if (!$user) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Admin user not found.',
            ]);
        }
    
        $user->delete(); 
        return response()->json([
            'status'    => 200,
            'message'   => 'Admin deleted successfully.',
        ]);
    }

    public function export(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Admin::query();

        // Filter by keyword
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->Where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $employees = $query->get();

        if ($employees->count() > 0) {
            $delimiter = ",";
            $filename = "employees_export_" . date('Y-m-d') . ".csv";
            $f = fopen('php://memory', 'w');

            // CSV Header
            $headers = [
                'Name',
                'Email',
            ];
            fputcsv($f, $headers, $delimiter);

            foreach ($employees as $employee) {
               
                $lineData = [
                    $employee->name,
                    $employee->email,
                ];

                fputcsv($f, $lineData, $delimiter);
            }

            fseek($f, 0);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            fpassthru($f);
            exit;
        } else {
            return redirect()->back()->with('error', 'No records found to export.');
        }
    }
}
