<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // private CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->categoryRepository->getSearchCategories($request->term);
        } /* elseif (!empty($request->status)) {
            $data = $this->categoryRepository->getAllCategories($request->status);
        } */ else {
            $data = $this->categoryRepository->getAllCategories();
        }

        return view('admin.category.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|regex:/^[\pL\s\-]+$/u|max:255",
            //"parent" => "required|alpha|max:255",
            "description" => "nullable|string",
            "icon_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "sketch_icon" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "image_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "banner_image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        $params = $request->except('_token');

        $categoryStore = $this->categoryRepository->createCategory($params);

        if ($categoryStore) {
            return redirect()->route('admin.category.index')->with('success', 'New Category Added!');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->categoryRepository->getCategoryById($id);
        return view('admin.category.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|regex:/^[\pL\s\-]+$/u|max:255",
            // "parent" => "required|alpha|max:255",
            "description" => "nullable|string",
            "icon_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "sketch_icon" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "image_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "banner_image" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        $params = $request->except('_token');

        $categoryStore = $this->categoryRepository->updateCategory($id, $params);

        if ($categoryStore) {
            return redirect()->route('admin.category.index')->with('success', 'Category updated successfully!');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $categoryStat = $this->categoryRepository->toggleStatus($id);

        if ($categoryStat) {
            return redirect()->route('admin.category.index');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }

    public function destroy($categoryId)
    {
        $this->categoryRepository->deleteCategory($categoryId);

        return redirect()->route('admin.category.index');
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
                    Category::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.category.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.category.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.category.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }
}
