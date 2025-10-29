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

    public function listById($id)
    {
        return Product::findOrFail($id);
    }

    public function listBySlug($slug)
    {
        return Product::where('slug', $slug)->where('status',1)->with('category')->first();
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);
        $cat_id = $product->cat_id;
        return Product::where('cat_id', $cat_id)->where('id', '!=', $id)->with('category')->get();
    }

    public function listImagesById($productId)
    {
        return ProductVariationImage::whereHas('productVariation', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get();
    }

    public function listVariationById($productId)
    {
        return ProductVariation::with('images')
            ->where('status',1)
            ->whereNotNull('weight')
            ->where('product_id', $productId)
            ->orderBy('weight','asc')
            ->get();
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
            //$newEntry->pack = $collectedData['pack'];
            $newEntry->gst = $collectedData['gst'] ?? 0;
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

            if (isset($data['variations']) && is_array($data['variations'])) {
                foreach ($data['variations'] as $variation) {
                   $variationModel = ProductVariation::create([
                        'product_id' => $newEntry->id,
                        'weight' => $variation['weight'],
                        'code' => $variation['code'],
                        'price' => $variation['price'],
                        'offer_price' => $variation['offer_price'] ?? null,
                        'stock'       => $variation['stock'] ?? 0,
                    ]);

                    if (!empty($variation['images'])) {
                        $variationUploadPath = "uploads/product/product-images/";
                        foreach ($variation['images'] as $imageFile) {
                            //$path = $imageFile->store('product_variations', 'public');
                            $imageName = time() . '-' . mt_rand() . '.' . $imageFile->getClientOriginalExtension();
                            $imageFile->move($variationUploadPath, $imageName);
                            ProductVariationImage::create([
                                'product_variation_id' => $variationModel->id, 
                                'image_path'           => $variationUploadPath . $imageName,
                            ]);
                        }
                    }
                }
            }
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
            //$updatedEntry->pack = $collectedData['pack'];
            $updatedEntry->gst = $collectedData['gst'] ?? 0;
            if (isset($newDetails['image'])) {
                // delete old image
                if (Storage::exists($updatedEntry->image)) unlink($updatedEntry->image);

                $image = $collectedData['image'];
                $imageName = $styleNoSlug . '-' . mt_rand() . '-' . time() . "." . $image->getClientOriginalExtension();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $updatedEntry->image = $upload_path . $uploadedImage;
            }

            $updatedEntry->save();
            if (isset($newDetails['variations']) && is_array($newDetails['variations'])) {
            foreach ($newDetails['variations'] as $variationData) {
          
                if (isset($variationData['id']) && $variation = ProductVariation::find($variationData['id'])) {
                
                    $variation->update([
                        'weight'      => $variationData['weight'],
                        'code'        => $variationData['code'],
                        'price'       => $variationData['price'],
                        'offer_price' => $variationData['offer_price'] ?? null,
                        'stock'       => $variationData['stock'] ?? 0,
                    ]);
                } else {
                 
                    $variation = ProductVariation::create([
                        'product_id'  => $updatedEntry->id,
                        'weight'      => $variationData['weight'],
                        'code'        => $variationData['code'],
                        'price'       => $variationData['price'],
                        'offer_price' => $variationData['offer_price'] ?? null,
                        'stock'       => $variationData['stock'] ?? 0,
                    ]);
                }
                if (!empty($variationData['images'])) {
                    $variationUploadPath = "uploads/product/product-images/";
                    foreach ($variationData['images'] as $imageFile) {
                        $imageName = time() . '-' . mt_rand() . '.' . $imageFile->getClientOriginalExtension();
                        $imageFile->move($variationUploadPath, $imageName);
                        //$path = $imageFile->store('product_variations', 'public');
                        ProductVariationImage::create([
                            'product_variation_id' => $variation->id,
                            'image_path'           => $variationUploadPath . $imageName,
                        ]);
                    }
                }
            }
        }

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

    public function delete($id)
    {
        Product::destroy($id);
    }

    public function deleteSingleImage($id)
    {
        ProductImage::destroy($id);
    }
    
    public function getProductDetailsBySlug($slug)
    {
     return Product::where('slug',$slug)->first();
    }

    public function categoryWiseProducts($cid,$id)
    {
        return;
   
    }

}
