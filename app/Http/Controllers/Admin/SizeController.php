<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\SizeInterface;
use App\Models\Size;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    // private SizeInterface $sizeRepository;

    // public function __construct(SizeInterface $sizeRepository)
    // {
    //     $this->sizeRepository = $sizeRepository;
    // }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $term = $request->term;
            $data = Size::where([['name', 'LIKE', '%' . $term . '%']])->get();
        } else {
            $data = Size::all();
        }

        return view('admin.size.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.size.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255",
        ]);

        // $params = $request->except('_token');
        // $storeData = $this->sizeRepository->createSize($params);

        $modelDetails = new Size;
        $modelDetails->name = $request->name;
        $modelDetails->save();

        if ($modelDetails) {
            return redirect()->route('admin.size.index')->with('success', 'Size added!');
        } else {
            return redirect()->route('admin.size.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = Size::findOrFail($id);
        return view('admin.size.details', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = Size::findOrFail($id);
        return view('admin.size.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255",
        ]);

        $size = Size::findOrFail($id);
        $size->name = $request->name;
        $size->save();

        if ($size) {
            return redirect()->route('admin.size.index')->with('success', 'Size updated!');
        } else {
            return redirect()->route('admin.size.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        // $storeData = $this->sizeRepository->toggleStatus($id);
        $size = Size::findOrFail($id);
        $status = ($size->status == 1) ? 0 : 1;
        $size->status = $status;
        $size->save();

        if ($size) {
            return redirect()->route('admin.size.index');
        } else {
            return redirect()->route('admin.size.create')->withInput($request->all());
        }
    }
}