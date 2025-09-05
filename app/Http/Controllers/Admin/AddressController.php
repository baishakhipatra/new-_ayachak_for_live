<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\AddressInterface;
use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    // private AddressInterface $addressRepository;

    public function __construct(AddressInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->addressRepository->getSearchAddress($request->term);
        } else {
            $data = $this->addressRepository->listAll();
        }
        $users = $this->addressRepository->listUsers();
        return view('admin.address.index', compact('data', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "nullable",
            "lng" => "nullable",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        ]);

        $params = $request->except('_token');
        $storeData = $this->addressRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.address.index')->with('success','New Address added successfully!');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->addressRepository->listById($id);
        $users = $this->addressRepository->listUsers();
        return view('admin.address.detail', compact('data', 'users'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "nullable",
            "lng" => "nullable",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        ]);

        $params = $request->except('_token');
        $storeData = $this->addressRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.address.index')->with('success','Address updated successfully!');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->addressRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.address.index');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->addressRepository->delete($id);

        return redirect()->route('admin.address.index');
    }
    public function bulkDestroy(Request $request)
    {
        // $request->validate([
        //     'bulk_action' => 'required',
        //     'delete_check' => 'required|array',
        // ]);

        $validator = Validator::make($request->all(), [
            'bulk_action' => 'required',
            'delete_check' => 'required|array',
        ], [
            'delete_check.*' => 'Please select at least one item'
        ]);

        if (!$validator->fails()) {
            if ($request['bulk_action'] == 'delete') {
                foreach ($request->delete_check as $index => $delete_id) {
                    Address::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.address.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.address.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.address.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }
}