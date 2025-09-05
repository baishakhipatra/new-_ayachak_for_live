<?php

namespace App\Repositories;

use App\Interfaces\SubcategoryInterface;
use App\Models\SubCategory;
use App\Interfaces\CategoryInterface;
use App\Models\Category;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;

class SubcategoryRepository implements SubcategoryInterface
{
    use UploadAble;

    public function getAllSubcategories()
    {
        // if ($status == null) {
        //     return SubCategory::all();
        // } else {
        //     $status == 'active' ? $stat_query = 1 : $stat_query = 0;
        return SubCategory::get();
        // }
    }

    public function getAllCategories()
    {
        return Category::all();
    }
    public function getSearchSubcategories(string $term)
    {
        return SubCategory::where([['name', 'LIKE', '%' . $term . '%']])->get();
    }

    public function getSubcategoryById($categoryId)
    {
        return SubCategory::findOrFail($categoryId);
    }

    public function deleteSubcategory($categoryId)
    {
        SubCategory::destroy($categoryId);
    }

    public function createSubcategory(array $categoryDetails)
    {
        $collection = collect($categoryDetails);

        $category = new SubCategory;
        $category->cat_id = $collection['cat_id'];
        $category->name = $collection['name'];
        $category->description = $collection['description'];
        $category->slug = $collection['slug'];

        $upload_path = "uploads/sub-category/";
        $image = $collection['image_path'];
        $imageName = time() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->image_path = $upload_path . $uploadedImage;

        $category->save();

        return $category;
    }

    public function updateSubcategory($categoryId, array $newDetails)
    {
        $category = SubCategory::findOrFail($categoryId);
        $collection = collect($newDetails);
        // dd($newDetails);

        if (!empty($collection['cat_id'])) {
            $category->cat_id = $collection['cat_id'];
        }
        $category->name = $collection['name'];
        $category->description = $collection['description'];
        $category->slug = $collection['slug'];

        // if (in_array('image_path', $newDetails)) {
        if (isset($newDetails['image_path'])) {
            // dd('here');
            $upload_path = "uploads/sub-category/";
            $image = $collection['image_path'];
            $imageName = time() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->image_path = $upload_path . $uploadedImage;
        }
        // dd('outside');

        $category->save();

        return $category;
    }

    public function toggleStatus($id)
    {
        $category = SubCategory::findOrFail($id);

        $status = ($category->status == 1) ? 0 : 1;
        $category->status = $status;
        $category->save();

        return $category;
    }
}