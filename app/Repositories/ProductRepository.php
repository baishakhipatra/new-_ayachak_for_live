<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\Sale;
use App\Models\Trend;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use App\Models\Wishlist;
use App\Models\ProductVariationImage;
use App\Models\ProductVariation;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductInterface
{
    use UploadAble;

    public function listAll()
    {
        return Product::all();
    }

    public function categoryList()
    {
        return Category::where('status',1)->get();
    }
    public function getSearchProducts(string $term)
    {
        return Product::where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('offer_price', 'LIKE', '%' . $term . '%')
            ->orWhere('style_no', 'LIKE', '%' . $term . '%')
            ->orWhere('price', 'LIKE', '%' . $term . '%')
            ->get();
    }

    public function filteredProducts(string $catagoryfilter = '', string $rangefilter = '', string $term = '')
    {
        $data = Product::where('status', 1);

        if ($catagoryfilter != '')
            $data =  $data->where('cat_id', $catagoryfilter);

        if ($rangefilter != '')
            $data = $data->where('collection_id', $rangefilter);

        // dd($data->get());

        if ($term != '')
            $data = $data->where('name', 'LIKE', '%' . $term . '%')->orWhere('offer_price', 'LIKE', '%' . $term . '%')->orWhere('style_no', 'LIKE', '%' . $term . '%')->orWhere('price', 'LIKE', '%' . $term . '%');

        return $data->get();
    }


    public function subCategoryList()
    {
        return SubCategory::all();
    }

    public function collectionList()
    {
        return Collection::all();
    }

    public function colorList()
    {
        return Color::all();
    }

    public function colorListByName()
    {
        return Color::orderBy('name', 'asc')->get();
    }

    public function sizeList()
    {
        return Size::all();
    }

    public function listById($id)
    {
        return Product::findOrFail($id);
    }

    public function listBySlug($slug)
    {
        return Product::where('slug', $slug)->where('status',1)->with('category', 'subCategory', 'collection', 'colorSize')->first();
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);
        $cat_id = $product->cat_id;
        return Product::where('cat_id', $cat_id)->where('id', '!=', $id)->with('category', 'subCategory', 'collection', 'colorSize')->get();
    }

    public function listImagesById($productId)
    {
        return ProductVariationImage::whereHas('productVariation', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get();
    }

    public function listVariationById($productId)
    {
        return ProductVariation::with('images')->where('product_id', $productId)->get();
    }


    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $collectedData = collect($data);
            $newEntry = new Product;
            $newEntry->cat_id = $collectedData['cat_id']?? 0;
            $newEntry->sub_cat_id = $collectedData['sub_cat_id'] ?? 0;
            $newEntry->collection_id = $collectedData['collection_id']?? 0;
            $newEntry->name = $collectedData['name'];
            $newEntry->short_desc = $collectedData['short_desc'];
            $newEntry->desc = $collectedData['desc'] ?? '';
            $newEntry->price = $collectedData['price'];
            $newEntry->offer_price = $collectedData['offer_price'] ?? 0;
            $newEntry->meta_title = $collectedData['meta_title'];
            $newEntry->meta_desc = $collectedData['meta_desc'];
            $newEntry->meta_keyword = $collectedData['meta_keyword'];
            $newEntry->style_no = $collectedData['style_no'];
            $newEntry->pack = $collectedData['pack'];
            $newEntry->gst = $collectedData['gst'] ?? 0;
            //$newEntry->brand = $collectedData['brand'];
            //$newEntry->wash_care = $collectedData['wash_care'];
           // $newEntry->pattern = $collectedData['pattern'];
           // $newEntry->fabric = $collectedData['fabric'];
            // slug generate
            $slug = Str::slug($collectedData['name'].'-'.$collectedData['style_no'], '-');
            $slugExistCount = Product::where('slug', $slug)->count();
            if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
            $newEntry->slug = $slug;

            // main image handling
            $upload_path = "uploads/product/";
            $image = $collectedData['image'];
            $imageName = time() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $newEntry->image = $upload_path . $uploadedImage;
            $newEntry->save();

            // multiple image upload handling
            if (isset($data['product_images'])) {
                $multipleImageData = [];
                foreach ($data['product_images'] as $imagekey => $imagevalue) {
                    $imageName = mt_rand() . '-' . time() . "." . $image->getClientOriginalName();
                    $imagevalue->move($upload_path, $imageName);
                    $image_path = $upload_path . $imageName;
                    $multipleImageData[] = [
                        'product_id' => $newEntry->id,
                        'image' => $image_path
                    ];
                }
                if (count($multipleImageData) > 0) ProductImage::insert($multipleImageData);
            }

            // check color & size
            // dd($data['color'], $data['size']);

            // if (!empty($data['color']) && !empty($data['size'])) {
            //     $multipleColorData = [];

            //     foreach ($data['color'] as $colorKey => $colorValue) {
            //         $multipleColorData[] = [
            //             'product_id' => $newEntry->id,
            //             'color' => $colorValue,
            //         ];
            //     }

            //     foreach ($data['size'] as $sizeKey => $sizeValue) {
            //         $multipleColorData[$sizeKey]['size'] = $sizeValue;
            //     }

            //     // dd($multipleColorData);

            //     ProductColorSize::insert($multipleColorData);
            // }

            DB::commit();
            return $newEntry;
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }

    public function update($id, array $newDetails)
    {
        // dd($newDetails);

        DB::beginTransaction();

        try {
            $updatedEntry = Product::findOrFail($id);
            $styleNoSlug = Str::slug($updatedEntry->style_no, '-');
            $upload_path = "uploads/product/updated-images/" . $styleNoSlug . '/';
            // dd($updatedEntry);
            $collectedData = collect($newDetails);
            if (!empty($collectedData['cat_id'])) $updatedEntry->cat_id = $collectedData['cat_id'];
            if (!empty($collectedData['sub_cat_id'])) $updatedEntry->sub_cat_id = $collectedData['sub_cat_id'];
            if (!empty($collectedData['collection_id'])) $updatedEntry->collection_id = $collectedData['collection_id'];

            // slug generate
            if ($updatedEntry->name != $collectedData['name']) {
                $slug = \Str::slug($collectedData['name'].'-'.$collectedData['style_no'], '-');
                $slugExistCount = Product::where('slug', $slug)->count();
                if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
                $updatedEntry->slug = $slug;
            }

            $updatedEntry->name = $collectedData['name'];
            $updatedEntry->short_desc = $collectedData['short_desc'];
            $updatedEntry->desc = $collectedData['desc'] ?? '';
            $updatedEntry->price = $collectedData['price'];
            $updatedEntry->offer_price = $collectedData['offer_price'];

            $updatedEntry->meta_title = $collectedData['meta_title'];
            $updatedEntry->meta_desc = $collectedData['meta_desc'];
            $updatedEntry->meta_keyword = $collectedData['meta_keyword'];
            $updatedEntry->style_no = $collectedData['style_no'];
            $updatedEntry->pack = $collectedData['pack'];
            $updatedEntry->gst = $collectedData['gst'];
            // $updatedEntry->brand = $collectedData['brand'];
            // $updatedEntry->wash_care = $collectedData['wash_care'];
            // $updatedEntry->pattern = $collectedData['pattern'];
            // $updatedEntry->fabric = $collectedData['fabric'];
            if (isset($newDetails['image'])) {
                // delete old image
                if (Storage::exists($updatedEntry->image)) unlink($updatedEntry->image);

                $image = $collectedData['image'];
                $imageName = $styleNoSlug . '-' . mt_rand() . '-' . time() . "." . $image->getClientOriginalExtension();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $updatedEntry->image = $upload_path . $uploadedImage;
            }

			if (isset($newDetails['size_chart_image'])) {
                // delete old image
                if (Storage::exists($updatedEntry->size_chart_image)) unlink($updatedEntry->size_chart_image);

                $image = $collectedData['size_chart_image'];
                $imageName = $styleNoSlug . '-' . mt_rand() . '-' . time() . "." . $image->getClientOriginalExtension();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $updatedEntry->size_chart_image = $upload_path . $uploadedImage;
            }

            $updatedEntry->save();

            // multiple image upload handling
            /* if (isset($newDetails['product_images'])) {
                $multipleImageData = [];
                foreach ($newDetails['product_images'] as $imagekey => $imagevalue) {
                    $imageName = mt_rand() . '-' . time() . "." . $image->getClientOriginalExtension();
                    $imagevalue->move($upload_path, $imageName);
                    $image_path = $upload_path . $imageName;
                    $multipleImageData[] = [
                        'product_id' => $id,
                        'image' => $image_path
                    ];
                }

                // dd($multipleImageData);

                if (count($multipleImageData) > 0) {
                    ProductImage::insert($multipleImageData);
                }
            } */
            // dd('out');

            DB::commit();
            return $updatedEntry;
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }

    public function toggle($id)
    {
        $updatedEntry = Product::findOrFail($id);

        $status = ($updatedEntry->status == 1) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function sale($id)
    {
        $saleExist = Sale::where('product_id', $id)->first();

        if ($saleExist) {
            $resp = Sale::where(['product_id' => $id])->delete();
            return $resp;
        } else {
            $resp = Sale::create(['product_id' => $id]);
            return $resp;
        }
    }

    public function delete($id)
    {
        Product::destroy($id);
    }

    public function deleteSingleImage($id)
    {
        ProductImage::destroy($id);
    }

    public function wishlistCheck($productId)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = Wishlist::where('product_id', $productId)->where('ip', $ip)->first();
        return $data;
    }

    public function primaryColorSizes($productId)
    {
        $primaryColor = ProductColorSize::select('color', 'id')->where('product_id', $productId)->groupBy('color')->orderBy('color')->first();

        // dd($primaryColor->color);

        if ($primaryColor) {
            $sizes = ProductColorSize::where('product_id', $productId)->where('color', $primaryColor->color)->orderBy('size')->get();
            // dd($sizes);
            // return $sizes;
              // Fetch images for the primary color
            $images = ProductImage::where('product_id', $productId)->where('color_id', $primaryColor->color)->get();
            // return $images;
            return [
                'sizes' => $sizes,
                'images' => $images
            ];
        }
        return false;
    }
    public function SelectedColorSizes($productId, $color_id)
    {
        $primaryColor = ProductColorSize::select('color', 'id')->where('product_id', $productId)->where('color', $color_id)->groupBy('color')->orderBy('color')->first();

        // dd($primaryColor->color);

        if ($primaryColor) {
            $sizes = ProductColorSize::where('product_id', $productId)->where('color', $primaryColor->color)->orderBy('size')->get();
            // dd($sizes);
            // return $sizes;
              // Fetch images for the primary color
            $images = ProductImage::where('product_id', $productId)->where('color_id', $primaryColor->color)->get();
            // return $images;
            return [
                'sizes' => $sizes,
                'images' => $images
            ];
        }
        return false;
    }
    public function getProductDetailsBySlug($slug)
    {
     return Product::where('slug',$slug)->first();
    }
    public function getAvailableColorByProductId($id)
    {
        $colorIds  = ProductColorSize::select('color')->where('product_id', $id)->groupBy('color')->get()->pluck('color');
        $availableColors = Color::whereIn('id', $colorIds)->get();
        return $availableColors;
   
    }
    public function categoryWiseProducts($cid,$id)
    {
        return;
   
    }

}
