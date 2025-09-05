<?php

namespace App\Repositories;

use App\Interfaces\CollectionInterface;
use App\Models\Collection;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\ProductColorSize;
class CollectionRepository implements CollectionInterface
{
    use UploadAble;

    public function getAllCollections()
    {
        return Collection::all();
    }

    public function getSearchCollections(string $term)
    {
        return Collection::where([['name', 'LIKE', '%' . $term . '%']])->get();
    }

    public function getAllSizes()
    {
        return Size::all();
    }

    public function getAllColors()
    {
        return Color::all();
    }

    public function getCollectionById($collectionId)
    {
        return Collection::findOrFail($collectionId);
    }

    public function getCollectionBySlug($slug, array $request = null)
    {
        return Collection::where('slug', $slug)->with('ProductDetails')->first();
    }

    public function deleteCollection($collectionId)
    {
        Collection::destroy($collectionId);
    }

    public function createCollection(array $data)
    {
        $upload_path = "public/uploads/collection/";
        $collection = collect($data);

        $modelDetails = new Collection;
        $modelDetails->name = $collection['name'];
        $modelDetails->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Collection::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $modelDetails->slug = $slug;

        // icon image
        $image = $collection['icon_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->icon_path = $upload_path . $uploadedImage;

        // sketch icon
        $image = $collection['sketch_icon'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->sketch_icon = $upload_path . $uploadedImage;

        // thumb image
        $image = $collection['image_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->image_path = $upload_path . $uploadedImage;

        // banner image
        $bannerImage = $collection['banner_image'];
        $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
        $bannerImage->move($upload_path, $bannerImageName);
        $uploadedImage = $bannerImageName;
        $modelDetails->banner_image = $upload_path . $uploadedImage;

        $modelDetails->save();

        return $modelDetails;
    }

    public function updateCollection($id, array $newDetails)
    {
        $upload_path = "public/uploads/collection/";
        $modelDetails = Collection::findOrFail($id);
        $collection = collect($newDetails);
        // dd($newDetails);

        $modelDetails->name = $collection['name'];
        $modelDetails->description = $collection['description'];
        // $modelDetails->slug = $collection['slug'];

        // if (in_array('image_path', $newDetails)) {
        //     $image = $collection['image_path'];
        //     $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
        //     $image->move($upload_path, $imageName);
        //     $uploadedImage = $imageName;
        //     $modelDetails->image_path = $upload_path.$uploadedImage;
        // }

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Collection::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $modelDetails->slug = $slug;

        if (isset($newDetails['icon_path'])) {
            $image = $collection['icon_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->icon_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['sketch_icon'])) {
            $image = $collection['sketch_icon'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->sketch_icon = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['image_path'])) {
            $image = $collection['image_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->image_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['banner_image'])) {
            // dd('here');
            $bannerImage = $collection['banner_image'];
            $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
            $bannerImage->move($upload_path, $bannerImageName);
            $uploadedImage = $bannerImageName;
            $modelDetails->banner_image = $upload_path . $uploadedImage;
        }

        $modelDetails->save();

        return $modelDetails;
    }

    public function toggleStatus($id)
    {
        $collection = Collection::findOrFail($id);

        $status = ($collection->status == 1) ? 0 : 1;
        $collection->status = $status;
        $collection->save();

        return $collection;
    }

    public function productsByCollection(int $collectionId, array $filter = null)
    {
       /* try {
            $productsQuery = Product::where('collection_id', $collectionId)->where('status', 1);
            // $productsQuery = DB::statement('SELECT * FROM `products` WHERE collection_id = '.$collectionId);

            // handling collection
            if (isset($filter['collection'])) {
                foreach ($filter['collection'] as $collectionKey => $collectionValue) {
                    // if (count($filter['collection']) == 1) {
                    //     $products = $productsQuery->where('collection_id', $collectionValue);
                    // } else {
                    // $rawQuery = "(collection_id = '.$collectionValue.' OR )";
                    $products = $productsQuery->where(function ($query) {
                        $query->orWhere('collection_id', $collectionValue);
                    });
                    // }
                }
                // $products = $productsQuery->whereRaw("'".$rawQuery."'");
            }

            // category filter
            if (isset($filter['category'])) {
                $products = $productsQuery->where('cat_id', $filter['category'])->get();
                // return $products;
                // return $collectionId;
            }

            // handling sort by
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
            throw $th;
            return $th;
        }*/
		try {
            $sizeIds=[];
            $arr=[];
            $colorIds=[];
            $productsQuery = Product::where('collection_id', $collectionId)->where('status', 1)->with('colorSize');
            // handling collection
            if (isset($filter['collection'])) {
                foreach ($filter['collection'] as $collectionKey => $collectionValue) {
                    $products = $productsQuery->where(function ($query) {
                        $query->orWhere('collection_id', $collectionValue);
                    });
                    
                }
            }
            // category filter
            if (isset($filter['category'])) {
                $products = $productsQuery->where('cat_id', $filter['category']);
            }

            // handling sort by
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
            // handling price
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
            //dd($products);
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
            throw $th;
            return $th;
        }
    }
}
