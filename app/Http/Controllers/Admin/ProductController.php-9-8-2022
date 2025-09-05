<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\ProductImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // private ProductInterface $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->productRepository->getSearchProducts($request->term);
        } else {
            $data = $this->productRepository->listAll();
        }
        return view('admin.product.index', compact('data'));
    }

    public function create(Request $request)
    {
        $categories = $this->productRepository->categoryList();
        $sub_categories = $this->productRepository->subCategoryList();
        $collections = $this->productRepository->collectionList();
        $colors = $this->productRepository->colorList();
        $sizes = $this->productRepository->sizeList();
        return view('admin.product.create', compact('categories', 'sub_categories', 'collections', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            "cat_id" => "required|integer",
            "sub_cat_id" => "required|integer",
            "collection_id" => "required|integer",
            "name" => "required|string|max:255",
            "short_desc" => "required",
            "desc" => "required",
            "price" => "required|integer",
            "offer_price" => "required|integer",
            "meta_title" => "nullable",
            "meta_desc" => "nullable",
            "meta_keyword" => "nullable",
            "style_no" => "required",
            "image" => "required",
            "product_images" => "nullable|array",
            "color" => "nullable|array",
            "size" => "nullable|array",
        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.product.index');
        } else {
            return redirect()->route('admin.product.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->productRepository->listById($id);
        $images = $this->productRepository->listImagesById($id);
        return view('admin.product.detail', compact('data', 'images'));
    }

    public function size(Request $request)
    {
        $productId = $request->productId;
        $colorId = $request->colorId;

        $data = ProductColorSize::where('product_id', $productId)->where('color', $colorId)->get();

        $resp = [];

        foreach ($data as $dataKey => $dataValue) {
            $resp[] = [
                'variationId' => $dataValue->id,
                'sizeId' => $dataValue->size,
                'sizeName' => $dataValue->sizeDetails->name
            ];
        }

        return response()->json(['error' => false, 'data' => $resp]);
    }

    public function edit(Request $request, $id)
    {
        $categories = $this->productRepository->categoryList();
        $sub_categories = $this->productRepository->subCategoryList();
        $collections = $this->productRepository->collectionList();
        $data = $this->productRepository->listById($id);
        $colors = $this->productRepository->colorListByName();
        $sizes = $this->productRepository->sizeList();
        $images = $this->productRepository->listImagesById($id);

        \DB::statement("SET SQL_MODE=''");
        $productColorGroup = ProductColorSize::select('id', 'color', 'status')->where('product_id', $id)->groupBy('color')->orderBy('position')->orderBy('id')->get();

        return view('admin.product.edit', compact('id', 'data', 'categories', 'sub_categories', 'collections', 'images', 'colors', 'sizes', 'productColorGroup'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            "product_id" => "required|integer",
            "cat_id" => "nullable|integer",
            "sub_cat_id" => "nullable|integer",
            "collection_id" => "nullable|integer",
            "name" => "required|string|max:255",
            "short_desc" => "required",
            "desc" => "required",
            "price" => "required|integer",
            "offer_price" => "required|integer",
            "meta_title" => "nullable|string",
            "meta_desc" => "nullable|string",
            "meta_keyword" => "nullable|string",
            "style_no" => "required",
            "image" => "nullable",
            "product_images" => "nullable|array",
        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->update($request->product_id, $params);

        if ($storeData) {
            return redirect()->route('admin.product.index')->with('success', 'Product updated successfully');
        } else {
            return redirect()->route('admin.product.update', $request->product_id)->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->productRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.product.index');
        } else {
            return redirect()->route('admin.product.create')->withInput($request->all());
        }
    }

    public function sale(Request $request, $id)
    {
        $storeData = $this->productRepository->sale($id);

        // if ($storeData) {
        return redirect()->route('admin.product.index');
        // } else {
        //     return redirect()->route('admin.product.create')->withInput($request->all());
        // }
    }

    public function trending(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_trending == 1) {
            $product->is_trending = 0;
        } else {
            $product->is_trending = 1;
        }
        $product->save();

        return redirect()->route('admin.product.index');
    }

    public function destroy(Request $request, $id)
    {
        $this->productRepository->delete($id);

        return redirect()->route('admin.product.index');
    }

    public function destroySingleImage(Request $request, $id)
    {
        $this->productRepository->deleteSingleImage($id);
        return redirect()->back();

        // return redirect()->route('admin.product.index');
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
                    Product::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.product.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.product.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.product.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }

    public function variationSizeDestroy(Request $request, $id)
    {
        // dd($id);
        ProductColorSize::destroy($id);
        return redirect()->back();
    }

    public function variationImageDestroy(Request $request)
    {
        // dd($request->all());
        ProductImage::destroy($request->id);
        return response()->json(['status' => 200, 'message' => 'Image deleted successfully']);
        // return redirect()->back();
    }

    public function variationImageUpload(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'product_id' => 'required',
            'color_id' => 'required',
            'image' => 'required|array',
        ]);

        $product_id = $request->product_id;
        $color_id = $request->color_id;

        // dd($request->image);

        foreach($request->image as $imageKey => $imageValue) {
            // $newName = str_replace(' ', '-', $imageValue->getClientOriginalName());
            $newName = mt_rand().'_'.time().'.'.$imageValue->getClientOriginalExtension();
            $imageValue->move('public/uploads/product/product_images/', $newName);

            $productImage = new ProductImage();
            $productImage->product_id = $product_id;
            $productImage->color_id = $color_id;
            $productImage->image = 'public/uploads/product/product_images/'.$newName;
            $productImage->save();
        }

        return redirect()->back();
    }

    public function variationSizeUpload(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'color_id' => 'required',
            'size' => 'required',
            'price' => 'required',
            'offer_price' => 'required',
        ]);

        if (!$validator->fails()) {
            $productImage = new ProductColorSize();
            $productImage->product_id = $request->product_id;
            $productImage->color = $request->color_id;
            $productImage->size = $request->size;
            $productImage->assorted_flag = $request->assorted_flag ? $request->assorted_flag : 0;
            $productImage->price = $request->price;
            $productImage->offer_price = $request->offer_price;
            $productImage->stock = $request->stock ? $request->stock : 0;
            $productImage->code = $request->code ? $request->code : 0;
            $productImage->save();

            // return response()->json(['status' => 200, 'message' => 'Size added successfully']);
            return redirect()->back();
        } else {
            // return response()->json(['status' => 200, 'message' => $validator->errors()->first()]);
            return redirect()->back()->with('failure', $validator->errors()->first())->withInput($request->all());

            // return redirect()->route('admin.product.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }

        /* $request->validate([
            'product_id' => 'required',
            'color_id' => 'required',
            'size' => 'required',
            'price' => 'required',
            'offer_price' => 'required',
        ]);

        $productImage = new ProductColorSize();
        $productImage->product_id = $request->product_id;
        $productImage->color = $request->color_id;
        $productImage->size = $request->size;
        $productImage->assorted_flag = $request->assorted_flag ? $request->assorted_flag : 0;
        $productImage->price = $request->price;
        $productImage->offer_price = $request->offer_price;
        $productImage->stock = $request->stock ? $request->stock : 0;
        $productImage->code = $request->code ? $request->code : 0;
        $productImage->save();

        // return redirect()->back();
        return response()->json(['status' => 200, 'message' => 'Size added successfully']); */
    }

    public function variationColorDestroy(Request $request, $productId, $colorId)
    {
        // dd($productId, $colorId);
        ProductColorSize::where('product_id', $productId)->where('color', $colorId)->delete();
        return redirect()->back();
    }

    public function variationColorAdd(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'product_id' => 'required',
            'color' => 'required',
            'size' => 'required',
            'price' => 'required',
            'offer_price' => 'required',
        ]);

        $productImage = new ProductColorSize();
        $productImage->product_id = $request->product_id;
        $productImage->color = $request->color;
        $productImage->size = $request->size;
        $productImage->assorted_flag = $request->assorted_flag ? $request->assorted_flag : 0;
        $productImage->price = $request->price;
        $productImage->offer_price = $request->offer_price;
        $productImage->stock = $request->stock ? $request->stock : 0;
        $productImage->code = $request->code ? $request->code : 0;
        $productImage->save();

        return redirect()->back();
    }

    public function variationColorPosition(Request $request)
    {
        // dd($request->all());
        $position = $request->position;
        $i = 1;
        foreach ($position as $key => $value) {
            $banner = ProductColorSize::findOrFail($value);
            $banner->position = $i;
            $banner->save();
            $i++;
        }
        return response()->json(['status' => 200, 'message' => 'Position updated']);
    }

    public function variationStatusToggle(Request $request)
    {
        // dd($request->all());
        $data = ProductColorSize::where('product_id', $request->productId)->where('color', $request->colorId)->first();

        if ($data) {
            if ($data->status == 1) {
                $status = 0;
                $statusType = 'inactive';
                $statusMessage = 'Color is inactive';
            } else {
                $status = 1;
                $statusType = 'active';
                $statusMessage = 'Color is active';
            }

            $data->status = $status;
            $data->save();
            return response()->json(['status' => 200, 'type' => $statusType, 'message' => $statusMessage]);
        } else {
            return response()->json(['status' => 400, 'message' => 'Something happened']);
        }
    }
}
