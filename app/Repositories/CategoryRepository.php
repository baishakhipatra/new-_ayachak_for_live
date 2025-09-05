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

    public function getAllSizes()
    {
        return Size::all();
    }

    public function getAllColors()
    {
        return Color::all();
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

    public function productsByCategory(int $categoryId, array $filter = null)
    {
       /* try {
            $productsQuery = Product::where('cat_id', $categoryId)->where('status', 1);

            // collection handling
            if (isset($filter['collection'])) {
                if (count($filter['collection']) == 1) {
                    $products = $productsQuery->where('collection_id', $filter['collection'][0]);
                } else {

                    // dd($filter['collection']);

                    // $products = $productsQuery->where([function ($query) {
                    //     foreach ($filter['collection'] as $collectionKey => $collectionValue) {
                    //         $query->orWhere('collection_id', $collectionValue);
                    //     }
                    // }]);

                    // $data = Borrower::where([
                    //     [function ($query) use ($request) {
                    //         if ($term = $request->term) {
                    //             $query
                    //                 ->orWhere('name_prefix', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('full_name', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('email', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('mobile', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('pan_card_number', 'LIKE', '%' . $term . '%')
                    //                 ->get();
                    //         }
                    //     }]
                    // ])
                    // ->with(['agreement', 'borrowerAgreementRfq'])
                    // ->latest('id')
                    // ->paginate(15)
                    // ->appends(request()->query());



                    // $rawQuery = "(collection_id = '.$collectionValue.' OR )";
                    // $products = $productsQuery->where(function($query) {
                    //     $query->orWhere('collection_id', $collectionValue);
                    // });
                }
            }

            // order handling
            if (isset($filter['orderBy'])) {
                $orderBy = "id";
                $order = "desc";

                if ($filter['orderBy'] == "new_arr") {
                    $orderBy = "id";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "mst_viw") {
                    $orderBy = "view_count";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "prc_low") {
                    $orderBy = "offer_price";
                    $order = "asc";
                } elseif ($filter['orderBy'] == "prc_hig") {
                    $orderBy = "offer_price";
                    $order = "desc";
                }

                $products = $productsQuery->orderBy($orderBy, $order);
            }
			 if (isset($filter['price'])) {
                $amount = explode(',', $filter['price']);
                $minAmount = $amount[0];
                $maxAmount = $amount[1];


                $products = $productsQuery->whereRaw("offer_price >= '$minAmount' AND offer_price <= '$maxAmount'")->get();
            }
            $products = $productsQuery->with('colorSize')->get();
            
            $response = [];
            foreach ($products as $productKey => $productValue) {
                // price check
                if (count($productValue->colorSize) > 0) {
                    $varArray = [];
                    foreach ($productValue->colorSize as $productVariationKey => $productVariationValue) {
                        $varArray[] = $productVariationValue->offer_price;
                    }
                    $bigger = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($bigger < $varArray[$i]) {
                            $bigger = $varArray[$i];
                        }
                    }

                    $smaller = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($smaller > $varArray[$i]) {
                            $smaller = $varArray[$i];
                        }
                    }

                    $displayPrice = '&#8377;'.$smaller . ' - &#8377;' . $bigger;

                    if ($smaller == $bigger) $displayPrice = '&#8377;'.$smaller;
                    $show_price = $displayPrice;
                } else {
                    $show_price = '&#8377;'.$productValue['offer_price'];
                }

                // color check
                if (count($productValue->colorSize) > 0) {
                    $uniqueColors = [];

                    foreach ($productValue->colorSize as $variantKey => $variantValue) {
                        if (in_array_r($variantValue->colorDetails->code, $uniqueColors)) continue;

                        $uniqueColors[] = [
                            'id' => $variantValue->colorDetails->id,
                            'code' => $variantValue->colorDetails->code,
                            'name' => $variantValue->colorDetails->name,
                        ];
                    }

                    $colorVar = '<ul class="product__color">';
                    // echo count($uniqueColors);
                    foreach($uniqueColors as $colorCodeKey => $colorCode) {
                        if ($colorCodeKey == 5) break;
                        if ($colorCode['id'] == 61) {
                            $colorVar .= '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); " class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="Assorted"></li>';
                        } else {
                            $colorVar .= '<li onclick="sizeCheck('.$productValue->id.', '.$colorCode['id'].')" style="background-color: '.$colorCode['code'].'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorCode['name'].'"></li>';
                        }
                    }
                    if (count($uniqueColors) > 5) $colorVar .= '<li>+ more</li>';
                    $colorVar .= '</ul>';

                    $colorVariation = $colorVar;
                } else {
                    $colorVariation = '';
                }

                $response[] = [
                    'name' => $productValue['name'],
                    'slug' => $productValue['slug'],
                    'image' => $productValue['image'],
                    'styleNo' => $productValue['style_no'],
                    'displayPrice' => $show_price,
                    'colorVariation' => $colorVariation
                ];
            }

            return $response;
        } catch (\Throwable $th) {
            // throw $th;
            return $th;
        }*/
		try {
            $sizeIds=[];
            $arr=[];
            $colorIds=[];
            $productsQuery = Product::where('cat_id', $categoryId)->where('status', 1)->with('colorSize');

            // collection handling
            if (isset($filter['collection'])) {
                if (count($filter['collection']) == 1) {
                    $products = $productsQuery->where('collection_id', $filter['collection'][0]);
                } else {

                    // dd($filter['collection']);

                    // $products = $productsQuery->where([function ($query) {
                    //     foreach ($filter['collection'] as $collectionKey => $collectionValue) {
                    //         $query->orWhere('collection_id', $collectionValue);
                    //     }
                    // }]);

                    // $data = Borrower::where([
                    //     [function ($query) use ($request) {
                    //         if ($term = $request->term) {
                    //             $query
                    //                 ->orWhere('name_prefix', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('full_name', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('email', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('mobile', 'LIKE', '%' . $term . '%')
                    //                 ->orWhere('pan_card_number', 'LIKE', '%' . $term . '%')
                    //                 ->get();
                    //         }
                    //     }]
                    // ])
                    // ->with(['agreement', 'borrowerAgreementRfq'])
                    // ->latest('id')
                    // ->paginate(15)
                    // ->appends(request()->query());



                    // $rawQuery = "(collection_id = '.$collectionValue.' OR )";
                    // $products = $productsQuery->where(function($query) {
                    //     $query->orWhere('collection_id', $collectionValue);
                    // });
                }
            }

            // order handling
            if (isset($filter['orderBy'])) {
                $orderBy = "id";
                $order = "desc";

                if ($filter['orderBy'] == "new_arr") {
                    $orderBy = "id";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "mst_viw") {
                    $orderBy = "view_count";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "prc_low") {
                    $orderBy = "offer_price";
                    $order = "asc";
                } elseif ($filter['orderBy'] == "prc_hig") {
                    $orderBy = "offer_price";
                    $order = "desc";
                }

                $products = $productsQuery->orderBy($orderBy, $order);
            }
			 if (isset($filter['price'])) {
                $amount = explode(',', $filter['price']);
                $minAmount = $amount[0];
                $maxAmount = $amount[1];


                $products = $productsQuery->whereRaw("offer_price >= '$minAmount' AND offer_price <= '$maxAmount'");
            }
            // handling style no
            if (isset($filter['style'])) {
                $products = $productsQuery->whereIN('style_no', $filter['style']);
            }
            // handling size
            if (!empty($filter['size'])) {
                //foreach ($filter['size'] as $sizeKey => $sizeValue) {
                     //echo $sizeValue."<br/>";
                    //$products = $productsQuery->join('product_color_sizes', 'product_color_sizes.product_id', 'products.id')->where(function ($query)use($filter) {
                       // $query->orWhereIN('product_color_sizes.size', $filter['size']);
                  //  });
               // }
               $mySizeData = ProductColorSize::select('product_id')->whereIn('size',$filter['size'])->groupBy('product_id')->pluck('product_id');
               $sizeIds = array();
               foreach($mySizeData as $col){
                    $sizeIds[] = $col;
               }               
               
               //dd($sizeIds);
            }
             // handling color
             if (!empty($filter['color'])) {
               // foreach ($filter['color'] as $colorKey => $colorValue) {
                    // $products = $productsQuery->join('product_color_sizes', 'product_color_sizes.product_id', 'products.id')->where(function ($query) use($filter){
                    //     $query->orWhereIN('product_color_sizes.color', $filter['color']);
                    // });
               // }
               $myColorData = ProductColorSize::whereIn('color',$filter['color'])->groupBy('product_id')->pluck('product_id');
               $colorIds = array();
               foreach($myColorData as $col){
                    $colorIds[] = $col;
               }
            //    dd($colorIds);
            }
            $arr = array_merge($sizeIds,$colorIds);
            
            

            if(!empty($arr)){
                $products = $productsQuery->whereIN('id',$arr);
            }
            $products = $productsQuery->groupby('id')->get();
            
            $response = [];
            foreach ($products as $productKey => $productValue) {
                // price check
                if (count($productValue->colorSize) > 0) {
                    $varArray = [];
                    foreach ($productValue->colorSize as $productVariationKey => $productVariationValue) {
                        $varArray[] = $productVariationValue->offer_price;
                    }
                    $bigger = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($bigger < $varArray[$i]) {
                            $bigger = $varArray[$i];
                        }
                    }

                    $smaller = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($smaller > $varArray[$i]) {
                            $smaller = $varArray[$i];
                        }
                    }

                    $displayPrice = '&#8377;'.$smaller . ' - &#8377;' . $bigger;

                    if ($smaller == $bigger) $displayPrice = '&#8377;'.$smaller;
                    $show_price = $displayPrice;
                } else {
                    $show_price = '&#8377;'.$productValue['offer_price'];
                }

                // color check
               /* if (count($productValue->colorSize) > 0) {
                    $uniqueColors = [];

                    foreach ($productValue->colorSize as $variantKey => $variantValue) {
                        if (in_array_r($variantValue->colorDetails->code, $uniqueColors)) continue;

                        $uniqueColors[] = [
                            'id' => $variantValue->colorDetails->id,
                            'code' => $variantValue->colorDetails->code,
                            'name' => $variantValue->colorDetails->name,
                        ];
                    }

                    $colorVar = '<ul class="product__color">';
                    // echo count($uniqueColors);
                    foreach($uniqueColors as $colorCodeKey => $colorCode) {
                        if ($colorCodeKey == 5) break;
                        if ($colorCode['id'] == 61) {
                            $colorVar .= '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); " class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="Assorted"></li>';
                        } else {
                            $colorVar .= '<li onclick="sizeCheck('.$productValue->id.', '.$colorCode['id'].')" style="background-color: '.$colorCode['code'].'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorCode['name'].'"></li>';
                        }
                    }
                    if (count($uniqueColors) > 5) $colorVar .= '<li>+ more</li>';
                    $colorVar .= '</ul>';

                    $colorVariation = $colorVar;
                } else {
                    $colorVariation = '';
                }*/
                $colorVariation=variationColors($productValue->id, 5);
                $response[] = [
                    'name' => $productValue['name'],
                    'slug' => $productValue['slug'],
                    'image' => $productValue['image'],
                    'styleNo' => $productValue['style_no'],
                    'displayPrice' => $show_price,
                    'colorVariation' => $colorVariation
                ];
            }

            return $response;
        } catch (\Throwable $th) {
            // throw $th;
            return $th;
        }
    }
	
	 public function getAllCollections()
    {
        return Collection::all();
    }
}
