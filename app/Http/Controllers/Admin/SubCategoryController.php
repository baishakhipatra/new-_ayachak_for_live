<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\SubcategoryInterface;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    // private SubcategoryInterface $SubcategoryRepository;

    public function __construct(SubcategoryInterface $SubcategoryRepository)
    {
        $this->subcategoryRepository = $SubcategoryRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->subcategoryRepository->getSearchSubcategories($request->term);
        }
        // elseif (!empty($request->status)) {
        //     $data = $this->subcategoryRepository->getAllSubcategories($request->status);
        // }
        else {
            $data = $this->subcategoryRepository->getAllSubcategories();
        }
        $categories = $this->subcategoryRepository->getAllCategories();

        return view('admin.subcategory.index', compact('data', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "cat_id" => "required|integer",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = SubCategory::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);

        // send slug
        request()->merge(['slug' => $slug]);

        // $params = $request->only(['name', 'description', 'image_path', 'slug']);
        $params = $request->except('_token');

        $storeData = $this->subcategoryRepository->createSubcategory($params);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->subcategoryRepository->getSubcategoryById($id);
        $categories = $this->subcategoryRepository->getAllCategories();
        return view('admin.subcategory.detail', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "cat_id" => "nullable|integer",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = SubCategory::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);

        // send slug
        request()->merge(['slug' => $slug]);

        $params = $request->except('_token');

        $storeData = $this->subcategoryRepository->updateSubcategory($id, $params);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->subcategoryRepository->toggleStatus($id);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->subcategoryRepository->deleteSubcategory($id);

        return redirect()->route('admin.subcategory.index');
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
                    SubCategory::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.subcategory.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.subcategory.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.subcategory.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }
}