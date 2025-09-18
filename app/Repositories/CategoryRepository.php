<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use DB;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductColorSize;
class CategoryRepository implements CategoryInterface
{
    use UploadAble;

    public function getAllCategories()
    {
        return Category::orderBy('position', 'asc')->get();
    }

    public function getSearchCategories(string $term)
    {
        return Category::where([['name', 'LIKE', '%' . $term . '%']])->get();
    }

    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->with('ProductDetails')->first();
    }

    public function deleteCategory($categoryId)
    {
        
        Category::destroy($categoryId);
    }

    public function createCategory(array $categoryDetails)
    {
        $upload_path = "public/uploads/category/";
        $collection = collect($categoryDetails);

        $category = new Category;
        $category->name = $collection['name'];
       // $category->parent = $collection['parent'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Category::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $category->slug = $slug;

        // icon image
        $image = $collection['icon_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->icon_path = $upload_path . $uploadedImage;

        // sketch icon
        $image = $collection['sketch_icon'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->sketch_icon = $upload_path . $uploadedImage;

        // thumb image
        $image = $collection['image_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->image_path = $upload_path . $uploadedImage;

        // banner image
        $bannerImage = $collection['banner_image'];
        $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
        $bannerImage->move($upload_path, $bannerImageName);
        $uploadedImage = $bannerImageName;
        $category->banner_image = $upload_path . $uploadedImage;

        $category->save();

        return $category;
    }

    public function updateCategory($categoryId, array $newDetails)
    {
        $upload_path = "public/uploads/category/";
        $category = Category::findOrFail($categoryId);
        $collection = collect($newDetails);

        $category->name = $collection['name'];
        //$category->parent = $collection['parent'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Category::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $category->slug = $slug;

        if (isset($newDetails['icon_path'])) {
            // dd('here');
            $image = $collection['icon_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->icon_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['sketch_icon'])) {
            // dd('here');
            $image = $collection['sketch_icon'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->sketch_icon = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['image_path'])) {
            // dd('here');
            $image = $collection['image_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->image_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['banner_image'])) {
            // dd('here');
            $bannerImage = $collection['banner_image'];
            $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
            $bannerImage->move($upload_path, $bannerImageName);
            $uploadedImage = $bannerImageName;
            $category->banner_image = $upload_path . $uploadedImage;
        }
        // dd('outside');

        $category->save();

        return $category;
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);

        $status = ($category->status == 1) ? 0 : 1;
        $category->status = $status;
        $category->save();

        return $category;
    }
}
