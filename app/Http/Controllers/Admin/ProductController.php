<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Collection;
use App\Models\Category;
use App\Models\ProductColorSize;
use App\Models\ProductImage;
use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Return_;
use App\Models\ProductVariation;
use App\Models\ProductVariationImage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends Controller
{
    // private ProductInterface $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $catagory = !empty($request->category) ? $request->category : '';
        $range = !empty($request->range) ? $request->range : '';
        $term = !empty($request->term) ? $request->term : '';

        if (!empty($request->term) || !empty($request->category) || !empty($request->range)) {
            $data = $this->productRepository->filteredProducts($catagory, $range, $term);
        } else {
            $data = $this->productRepository->listAll();
        }

        $catagories = Product::select('cat_id')->groupBy('cat_id')->with('category')->get();
        $ranges = Product::select('collection_id')->groupBy('collection_id')->with('collection')->get();

        if ($request->ajax()) {
            $cid = $request->collection_id;
            $cc = Product::where('collection_id', $cid)->groupBy('cat_id')->select('cat_id')->with('category')->get();
            return json_encode($cc);
        }

        return view('admin.product.index', compact('data', 'catagories', 'ranges'));
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
            "cat_id" => "nullable",
            "sub_cat_id" => "nullable",
            "collection_id" => "nullable",
            "name" => "required|string|max:255",
            "short_desc" => "nullable",
            "desc" => "nullable",
            "price" => "required|integer",
            "offer_price" => "nullable|integer",
            "meta_title" => "nullable",
            "meta_desc" => "nullable",
            "meta_keyword" => "nullable",
            "style_no" => "nullable|unique:products",
            "image" => "required|mimes:jpg,jpeg,png,svg,gif,webp|max:10000000",
            'gst'=>'nullable|regex:/^[^\+\-\&\%]+$/',
            "pack" => "nullable|string|max:255",

        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.product.edit', $storeData->id)->with('success', 'New Product created, add Product Variation!');
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
        // $productColorGroup = ProductColorSize::select('id', 'color', 'status')->where('product_id', $id)->groupBy('color')->orderBy('position')->orderBy('id')->get();
        $productColorGroup = ProductColorSize::select('id', 'color', 'status', 'position', 'color_name', 'color_fabric')->where('product_id', $id)->groupBy('color')->orderBy('position')->orderBy('id')->get();

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
            "short_desc" => "nullable",
            "desc" => "nullable",
            "price" => "required|integer",
            "offer_price" => "required|integer",
            "meta_title" => "nullable|string",
            "meta_desc" => "nullable|string",
            "meta_keyword" => "nullable|string",
            "style_no" => "nullable",
            "image" => "nullable",
            "size_chart_image" => "nullable",
            "product_images" => "nullable|array",
            'gst'=>'nullable|regex:/^[^\+\-\&\%]+$/',
            "pack" => "nullable|string|max:255",
        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->update($request->product_id, $params);

        if ($storeData) {
            // return redirect()->route('admin.product.index')->with('success', 'Product updated successfully');
            return redirect()->back()->with('success', 'Product updated successfully');
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
    public function hotdeal(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_hotdeal == 1) {
            $product->is_hotdeal = 0;
        } else {
            $product->is_hotdeal = 1;
        }
        $product->save();

        return redirect()->route('admin.product.index');
    }
    public function feature(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_feature == 1) {
            $product->is_feature = 0;
        } else {
            $product->is_feature = 1;
        }
        $product->save();

        return redirect()->route('admin.product.index');
    }
    public function dealoftheday(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_deal_of_the_day == 1) {
            $product->is_deal_of_the_day = 0;
        } else {
            $product->is_deal_of_the_day = 1;
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

        // dd($request->all());

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
        return redirect()->back()->with('success', 'Size deleted successfully');
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

        foreach ($request->image as $imageKey => $imageValue) {
            // $newName = str_replace(' ', '-', $imageValue->getClientOriginalName());
            $newName = mt_rand() . '_' . time() . '.' . $imageValue->getClientOriginalExtension();
            $imageValue->move('public/uploads/product/product_images/', $newName);

            $productImage = new ProductImage();
            $productImage->product_id = $product_id;
            $productImage->color_id = $color_id;
            $productImage->image = 'uploads/product/product_images/' . $newName;
            $productImage->save();
        }

        return redirect()->back()->with('success', 'Images added successfully!');
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
        return redirect()->back()->with('success', 'Color variation deleted!');
    }

    public function variationColorAdd(Request $request)
    {
        // dd("offer");
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'color' => 'required',
            'size' => 'required',
            'price' => 'required',
            'offer_price' => 'nullable|lt:price',
            'sku_code' => 'required|unique:product_color_sizes,code',
        ],
    [
        'offer_price.lt'=> 'Offer price must be less than price'
    ]);

        if (!$validator->fails()) {

            $check = ProductColorSize::where('product_id', $request->product_id)->where('color', $request->color)->where('size', $request->size)->count();

            if ($check == 0) {
                $colorName = Color::select('name')->where('id', $request->color)->first();
                $sizeName = Size::select('name')->where('id', $request->size)->first();

                $productImage = new ProductColorSize();
                $productImage->product_id = $request->product_id;
                $productImage->color = $request->color;
                $productImage->color_name = $colorName->name;
                $productImage->size = $request->size;
                $productImage->size_name = $sizeName->name;
                $productImage->assorted_flag = $request->assorted_flag ? $request->assorted_flag : 0;
                $productImage->price = $request->price ?? 0;
                $productImage->offer_price = $request->offer_price ?? $request->price;
                $productImage->stock = $request->stock ? $request->stock : 0;
                $productImage->code = $request->sku_code ?? '';
                $productImage->save();

                return redirect()->back()->with('success', 'Color added successfully');
            } else {
                return redirect()->back()->with('failure', 'This color & size already exist. Select a different one.')->withInput($request->all());
            }
        } else {
            return redirect()->back()->with('failure', $validator->errors()->first())->withInput($request->all());
        }

        // dd($request->all());

        /* $request->validate([
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

        return redirect()->back(); */
    }

    public function variationColorRename(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'current_color2' => 'required|integer',
            'update_color_name' => 'required'
        ]);

        // $colorsCHeck = ProductColorSize::select('color')->where('product_id', $request->product_id)->groupBy('color')->pluck('color')->toArray();

        // if(in_array($request->update_color, $colorsCHeck)) {
        //     return redirect()->back()->with('failure', 'Color exists already');
        // }

        // $color = Color::findOrFail($request->update_color);

        ProductColorSize::where('product_id', $request->product_id)->where('color', $request->current_color2)->update(['color_name' => $request->update_color_name]);

        return redirect()->back()->with('success', 'Color name updated');
    }

    public function variationColorEdit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|integer',
            'current_color' => 'required|integer',
            'update_color' => 'required|integer'
        ]);

        $colorsCHeck = ProductColorSize::select('color')->where('product_id', $request->product_id)->groupBy('color')->pluck('color')->toArray();

        if (in_array($request->update_color, $colorsCHeck)) {
            return redirect()->back()->with('failure', 'Color exists already');
        }

        $color = Color::findOrFail($request->update_color);

        ProductColorSize::where('product_id', $request->product_id)->where('color', $request->current_color)->update(['color' => $request->update_color, 'color_name' => $color->name, 'color_fabric' => null]);
        return redirect()->back()->with('success', 'Color updated');
    }

   public function variationSizeEdit(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'size' => 'nullable',
            'size_details' => 'nullable',
            'price' => 'required',
            'offer_price' => 'nullable',
            'code' => 'required',
        ]);

        if (!$validator->fails()) {
            if (empty($request->size)) {
                ProductColorSize::where('id', $request->id)->update([
                    'size_details' => $request->size_details,
                    'price' => $request->price,
                    'offer_price' => $request->offer_price,
                    'code' => $request->code,
                ]);
            } else {
                // check if the size exists already
                $productColorSizeDetail = ProductColorSize::findOrFail($request->id);

                $check = ProductColorSize::where('product_id', $productColorSizeDetail->product_id)->where('color', $productColorSizeDetail->color)->where('size', $productColorSizeDetail->size)->count();

                if ($check == 0) {
                    $sizeName = Size::select('name')->where('id', $request->size)->first();

                    ProductColorSize::where('id', $request->id)->update([
                        'size' => $request->size,
                        'size_name' => $sizeName->name,
                        'size_details' => $request->size_details,
                        'price' => $request->price,
                        'offer_price' => $request->offfer_price,
                        'code' => $request->code,
                    ]);
                } else {
                    return redirect()->back()->with('failure', 'This color & size already exist for this product. Select a different one.')->withInput($request->all());
                }
            }

            return redirect()->back()->with('success', 'Size details updated successfully');
        } else {
            return redirect()->back()->with('failure', $validator->errors()->first())->withInput($request->all());
        }
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

    public function variationFabricUpload(Request $request)
    {
        // dd($request->all());

        $save_location = 'uploads/color/';
        $data = $request->image;
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $imageName = mt_rand() . '_' . time() . '.png';

        if (file_put_contents($save_location . $imageName, $data)) {
            // $user = Auth::user();
            // $user->image_path = $save_location.$imageName;
            // $user->save();
            // return response()->json(['error' => false, 'message' => 'Image updated', 'image' => asset($save_location.$imageName)]);

            $productVariation = ProductColorSize::where('product_id', $request->product_id)->where('color', $request->color_id)->get();

            foreach ($productVariation as $item) {
                $item->color_fabric = $save_location . $imageName;
                $item->save();
            }

            return response()->json(['error' => false, 'message' => 'Image uploaded', 'image' => asset($save_location . $imageName), 'color_id' => $request->color_id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong']);
        }
    }

    public function variationCSVUpload(Request $request)
    {   
        if (!empty($request->file)) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $valid_extension = array("csv");
            $maxFileSize = 50097152;
            if (in_array(strtolower($extension), $valid_extension)) {
                if ($fileSize <= $maxFileSize) {
                    $location = 'uploads/csv';
                    $file->move($location, $filename);
                    // $filepath = public_path($location . "/" . $filename);
                    $filepath = $location . "/" . $filename;

                    // dd($filepath);

                    $file = fopen($filepath, "r");
                    $importData_arr = array();
                    $i = 0;
                    while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
                        $num = count($filedata);
                        // Skip first row
                        if ($i == 0) {
                            $i++;
                            continue;
                        }
                        for ($c = 0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata[$c];
                        }
                        $i++;
                    }
                    fclose($file);
                    $successCount = 0;
                    foreach ($importData_arr as $importData) {
                        $insertData = array(
                            "PRODUCT_ID" => isset($importData[0]) ? $importData[0] : null,
                            "PRODUCT_STYLE_NO" => isset($importData[1]) ? $importData[1] : null,
                            "COLOR_MASTER" => isset($importData[2]) ? $importData[2] : null,
                            // "CUSTOM_COLOR_NAME" => isset($importData[2]) ? $importData[2] : null,
                            "SIZE" => isset($importData[3]) ? $importData[3] : null,
                            "PRICE" => isset($importData[4]) ? $importData[4] : null,
                            "OFFER_PRICE" => isset($importData[5]) ? $importData[5] : null,
                            "SKU_CODE" => isset($importData[6]) ? $importData[6] : null,
                            "STOCK" => isset($importData[7]) ? $importData[7] : 1,
                            // "COLOR_POSITION" => isset($importData[8]) ? $importData[8] : 1,
                            "STATUS" => isset($importData[8]) ? $importData[8] : 1
                        );

                        $resp = ProductColorSize::insertData($insertData, $successCount);
                        $successCount = $resp['successCount'];
                    }

                    Session::flash('message', 'CSV Import Complete. Total no of entries: ' . count($importData_arr) . '. Successfull: ' . $successCount . ', Failed: ' . (count($importData_arr) - $successCount));
                } else {
                    Session::flash('message', 'File too large. File must be less than 50MB.');
                }
            } else {
                Session::flash('message', 'Invalid File Extension. supported extensions are ' . implode(', ', $valid_extension));
            }
        } else {
            Session::flash('message', 'No file found.');
        }

        return redirect()->back();
    }
    public function ProductColorSizeVariationExportCSV(){
       
        $data = ProductColorSize::orderBy('product_id','ASC')->get();
        if(count($data)>0){
            $delimiter = ",";
            $fileName = "Product Color Size Variation Details-".date('d-m-Y').".csv";
            // Create a file pointer
            $f = fopen('php://memory', 'w');

            // Set Column Headers
            $header = array("PRODUCT_ID","PRODUCT_STYLE_NO","COLOR_MASTER","SIZE","PRICE","OFFER_PRICE","SKU_CODE","STATUS[1:ACTIVE,0:INACTIVE]");
            fputcsv($f,$header,$delimiter);

            $count =1;
            foreach($data as $key => $row){
                $exportData = array(
                    $row->product_id ? $row->product_id : '',
                    $row->product_style_no ? $row->product_style_no : '',
                    $row->color_name ? $row->color_name : '',
                    $row->size_name ? $row->size_name : '',
                    $row->price ? $row->price : '',      
                    $row->offer_price ? $row->offer_price : '',      
                    $row->code ? $row->code : '',      
                    $row->status ? $row->status : ''                       
                );
                // dd($exportData);
                fputcsv($f,$exportData,$delimiter);
                $count++;
            }
            fseek($f,0);
            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $fileName . '";');

            //output all remaining data on a file pointer
            fpassthru($f);

        }

    }

    public function productvariationCSVUpload(Request $request){
        if (!empty($request->file)) {
                    $file = $request->file('file');
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $tempPath = $file->getRealPath();
                    $fileSize = $file->getSize();
                    $mimeType = $file->getMimeType();
        
                    $valid_extension = array("csv");
                    $maxFileSize = 50097152;
                    if (in_array(strtolower($extension), $valid_extension)) {
                        if ($fileSize <= $maxFileSize) {
                            $location = 'uploads/csv';
                            $file->move($location, $filename);
                            // $filepath = public_path($location . "/" . $filename);
                            $filepath = $location . "/" . $filename;
        
                            // dd($filepath);
        
                            $file = fopen($filepath, "r");
                            $importData_arr = array();
                            $i = 0;
                            while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
                                $num = count($filedata);
                                // Skip first row
                                if ($i == 0) {
                                    $i++;
                                    continue;
                                }
                                for ($c = 0; $c < $num; $c++) {
                                    $importData_arr[$i][] = $filedata[$c];
                                }
                                $i++;
                            }
                            fclose($file);
                            $successCount = 0;
        
                            foreach ($importData_arr as $importData) {
                                // if(empty($importData[0])){
                                //     Session::flash('message', 'Category name must be required');
                                //     return redirect()->back();
                                // }
                                if(empty($importData[1])){
                                    Session::flash('message', 'Style no must be required');
                                    return redirect()->back();
                                }
                                if(!isset($importData[2])){
                                    Session::flash('message', 'Product name must be required');
                                    return redirect()->back();
                                }
                                if(!isset($importData[3])){
                                    Session::flash('message', 'Price must be required');
                                    return redirect()->back();
                                }
                                // if(!isset($importData[4])){
                                //     Session::flash('message', 'Offer Price must be required');
                                //     return redirect()->back();
                                // }
                                if(!isset($importData[8])){
                                    Session::flash('message', 'GST must be required');
                                    return redirect()->back();
                                }
                                // if(empty($importData[9])){
                                //     Session::flash('message', 'Wash care must be required');
                                //     return redirect()->back();
                                // }




                                $catId='';
                                $colorId='';
                                $sizeId='';
                                if(!empty($importData[0])){
                                $CatsExistCheck = Category::where('name', $importData[0])->first();
                                            if ($CatsExistCheck) {
                                                $insertdistributorCatsId = $CatsExistCheck->id;
                                                $catId .= $insertdistributorCatsId;
        
                                            } else {
                                                    $dirCat = new Category();
                                                    $dirCat->name = $importData[0];
                                                    $dirCat->save();
                                                    $insertdistributorCatsId = $dirCat->id;
        
                                                    $catId .= $insertdistributorCatsId;
                                                }
                                            }
                                                // dd($importData);
                                 // slug generate
                                $slug = \Str::slug($importData[2].'-'.$importData[1], '-');
                                $slugExistCount = Product::where('slug', $slug)->count();
                                if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
                                $insertData = array(
                                    "cat_id" => $catId ? $catId: null,
                                    "style_no" => isset($importData[1]) ? $importData[1] : null,
                                    "name" => isset($importData[2]) ? $importData[2] : null,
                                    // "size" => isset($importData[6]) ? $importData[6] : null,
                                    "price" => isset($importData[3]) ? $importData[3] : null,
                                    "offer_price" => isset($importData[4]) ? $importData[4] : null,
                                    "brand" => isset($importData[5]) ? $importData[5] : null,
                                    "fabric" => isset($importData[6]) ? $importData[6] : null,
                                    "pattern" => isset($importData[7]) ? $importData[7] : null,
                                    "gst" => isset($importData[8]) ? $importData[8] : null,
                                    "wash_care" => isset($importData[9]) ? $importData[9] : null,
                                    "short_desc" => isset($importData[10]) ? $importData[10] : null,
                                    "status" => isset($importData[11]) ? $importData[11] : 1,
                                    "image" => 'backend_asset/images/defaul-product-image.png',
                                    "slug"=>$slug,
                                    "created_at" => now(),
                                    "updated_at"=> now(),
                                );
        
                                $resp = Product::insertProductData($insertData,$successCount);
                                $successCount = $resp['successCount'];
        
        
                            }
        
                            Session::flash('message', 'CSV Import Complete. Total no of entries: ' . count($importData_arr) . '. Successfull: ' . $successCount . ', Failed: ' . (count($importData_arr) - $successCount));
                        } else {
                            Session::flash('message', 'File too large. File must be less than 50MB.');
                        }
                    } else {
                        Session::flash('message', 'Invalid File Extension. supported extensions are ' . implode(', ', $valid_extension));
                    }
                } else {
                    Session::flash('message', 'No file found.');
                }
        
                return redirect()->back();
    }

    // public function productvariationCSVUpload(Request $request)
    // {
    //     dd('hii only product upload');  
    //     if (!empty($request->file)) {
    //         $file = $request->file('file');
    //         $filename = $file->getClientOriginalName();
    //         $extension = $file->getClientOriginalExtension();
    //         $tempPath = $file->getRealPath();
    //         $fileSize = $file->getSize();
    //         $mimeType = $file->getMimeType();

    //         $valid_extension = array("csv");
    //         $maxFileSize = 50097152;
    //         if (in_array(strtolower($extension), $valid_extension)) {
    //             if ($fileSize <= $maxFileSize) {
    //                 $location = 'public/uploads/csv';
    //                 $file->move($location, $filename);
    //                 // $filepath = public_path($location . "/" . $filename);
    //                 $filepath = $location . "/" . $filename;

    //                 // dd($filepath);

    //                 $file = fopen($filepath, "r");
    //                 $importData_arr = array();
    //                 $i = 0;
    //                 while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
    //                     $num = count($filedata);
    //                     // Skip first row
    //                     if ($i == 0) {
    //                         $i++;
    //                         continue;
    //                     }
    //                     for ($c = 0; $c < $num; $c++) {
    //                         $importData_arr[$i][] = $filedata[$c];
    //                     }
    //                     $i++;
    //                 }
    //                 fclose($file);
    //                 $successCount = 0;

    //                 foreach ($importData_arr as $importData) {
    //                     $catId='';
    //                     $colorId='';
    //                     $sizeId='';
    //                     $CatsExistCheck = Category::where('name', $importData[0])->first();
    //                                 if ($CatsExistCheck) {
    //                                     $insertdistributorCatsId = $CatsExistCheck->id;
    //                                     $catId .= $insertdistributorCatsId;

    //                                 } else {
    //                                         $dirCat = new Category();
    //                                         $dirCat->name = $importData[0];
    //                                         $dirCat->save();
    //                                         $insertdistributorCatsId = $dirCat->id;

    //                                         $catId .= $insertdistributorCatsId;
    //                                     }

    //                     $ColorsExistCheck = Color::where('name', $importData[4])->first();
    //                                 if ($ColorsExistCheck) {
    //                                     $insertcolorId = $ColorsExistCheck->id;
    //                                     $colorId .= $insertcolorId;

    //                                 } else {
    //                                         $dirCat = new Color();
    //                                         $dirCat->name = $importData[4];
    //                                         $dirCat->save();
    //                                         $insertcolorId = $dirCat->id;

    //                                         $colorId .= $insertcolorId;
    //                                     }
    //                                     $SizeExistCheck = Size::where('name', $importData[6])->first();
    //                                 if ($SizeExistCheck) {
    //                                     $insertsizeId = $SizeExistCheck->id;
    //                                     $sizeId .= $insertsizeId;

    //                                 } else {
    //                                         $dirCat = new Size();
    //                                         $dirCat->name = $importData[6];
    //                                         $dirCat->save();
    //                                         $insertsizeId = $dirCat->id;

    //                                         $sizeId .= $insertsizeId;
    //                                     }
    //                     $insertData = array(
    //                         "cat_id" => $catId ? $catId: null,
    //                         "style_no" => isset($importData[1]) ? $importData[1] : null,
    //                         "name" => isset($importData[2]) ? $importData[2] : null,
    //                         "size" => isset($importData[6]) ? $importData[6] : null,
    //                         "price" => isset($importData[7]) ? $importData[7] : null,
    //                         "offer_price" => isset($importData[8]) ? $importData[8] : null,
    //                         "brand" => isset($importData[9]) ? $importData[9] : null,
    //                         "fabric" => isset($importData[10]) ? $importData[10] : null,
    //                         "pattern" => isset($importData[11]) ? $importData[11] : null,
    //                         "gst" => isset($importData[12]) ? $importData[12] : null,
    //                         "wash_care" => isset($importData[13]) ? $importData[13] : null,
    //                         "short_desc" => isset($importData[14]) ? $importData[14] : null,
    //                         "status" => isset($importData[17]) ? $importData[17] : 1,
    //                         "created_at" => now(),
    //                         "updated_at"=> now(),
    //                     );

    //                     $resp = Product::insertGetId($insertData);
    //                     $userId = $resp['id'];
    //                     $successCount = $successCount++;
    //                     $color=new ProductColorSize();
    //                     $color->product_id=$userId;
    //                     $color->product_style_no= $importData[1];
    //                     $color->color=$colorId;
    //                     $color->color_name=$importData[4];
    //                     $color->size=$sizeId;
    //                     $color->size_name=$importData[6];
    //                     $color->price=$importData[7];
    //                     $color->offer_price=$importData[8];
    //                     $color->stock=$importData[15];
    //                     $color->code=$importData[16];
    //                     $color->position=$importData[5];
    //                     $color->status=1;
    //                     $color->save();


    //                 }

    //                 Session::flash('message', 'CSV Import Complete. Total no of entries: ' . count($importData_arr) . '. Successfull: ' . $successCount . ', Failed: ' . (count($importData_arr) - $successCount));
    //             } else {
    //                 Session::flash('message', 'File too large. File must be less than 50MB.');
    //             }
    //         } else {
    //             Session::flash('message', 'Invalid File Extension. supported extensions are ' . implode(', ', $valid_extension));
    //         }
    //     } else {
    //         Session::flash('message', 'No file found.');
    //     }

    //     return redirect()->back();
    // }

   

    public function variationBulkEdit(Request $request)
    {
        $request->validate([
            "bulkAction" => "required | in:edit",
            "variation_id" => "required | array",
        ]);
        $data = $request->variation_id;

        return view('admin.product.bulk.edit', compact('data', 'request'));
    }

    public function variationBulkUpdate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            "id" => "required|array",
            // "price" => "required|array",
            "offer_price" => "required|array"
        ]);

        // dd('here');

        foreach ($request->id as $key => $value) {
            // $price = $request->price[$key];
            $offer_price = $request->offer_price[$key];

            DB::table('product_color_sizes')
                ->where('id', $value)
                ->update([
                    // 'price' => $price,
                    'offer_price' => $offer_price
                ]);
        }

        return redirect()->route('admin.product.edit', $request->product_id)->with('success', 'Bulk update successfull');
    }

   public function exportAll(Request $request)
    {
        $data = Product::orderBy('id','ASC')->get();

        if(count($data)>0){
            $delimiter = ",";
            $fileName = "Product Details-".date('d-m-Y').".csv";
            // Create a file pointer
            $f = fopen('php://memory', 'w');

            // Set Column Headers
            $header = array("PRODUCT_ID","CATEGORY_ID","NAME","PRODUCT_STYLE_NO","POSITION","PRICE","OFFER_PRICE","GST","STATUS[1:ACTIVE,0:INACTIVE]");
            fputcsv($f,$header,$delimiter);

            $count =1;
            foreach($data as $key => $row){
                $exportData = array(
                    $row->id ? $row->id : '',
                    $row->cat_id ? $row->cat_id : '',
                    //$row->sub_cat_id ? $row->sub_cat_id : '',
                    $row->name ? $row->name : '',
                    $row->product_style_no ? $row->product_style_no : '',
                    $row->position ? $row->position : '',
                    $row->price ? $row->price : '',      
                    $row->offer_price ? $row->offer_price : '',      
                    // $row->brand ? $row->brand : '',
                    // $row->wash_care ? $row->wash_care : '',      
                    // $row->pattern ? $row->pattern : '',      
                    // $row->fabric ? $row->fabric : '',      
                    $row->gst ? $row->gst : '',      
                    $row->status ? $row->status : ''                       
                );
                // dd($exportData);
                fputcsv($f,$exportData,$delimiter);
                $count++;
            }
            fseek($f,0);
            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $fileName . '";');

            //output all remaining data on a file pointer
            fpassthru($f);

        }
    }

    public function productSkuList(Request $request)
    {
        $products = ProductVariation::with('product');

        if (!empty($request->term)) {
            $term = $request->term;
            $products = $products->where(function ($query) use ($term) {
                $query->where('code', 'like', '%' . $term . '%')
                ->orWhereHas('product', function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('style_no', 'like', '%' . $term . '%');
                });
            });
        }

        $products = $products->where('code', '!=', '')
                            ->orderBy('id', 'asc')
                            ->paginate(20);

        return view('admin.product.product-sku', compact('products'));
    }

    public function uploadImages(Request $request){
        $request->validate([
            'product_variation_id' => 'required|exists:product_variation,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('upload/product/product-images/');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $image->move($destinationPath, $imageName);
                ProductVariationImage::create([
                    'product_variation_id' => $request->product_variation_id,
                    'image_path' => 'upload/product/product-images/' . $imageName,
                ]);
            }
        }

        return back()->with('success', 'Images uploaded successfully!');
    }


    public function getVariationImages($id)
    {
        $images = ProductVariationImage::where('product_variation_id', $id)->get();

        return response()->json($images);
    }

    public function deleteVariationImage($id)
    {
        $image = ProductVariationImage::findOrFail($id);

        $filePath = public_path($image->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }


    public function ProductSkuEdit($id)
    {
        $variation = ProductVariation::find($id);
        return response()->json($variation);
    }

    public function ProductSkuUpdate(Request $request)
    {
        $request->validate([
            'id'           => 'required|exists:product_variation,id',
            'code'         => 'required|string|max:255',
            'weight'       => 'nullable|string|max:100',
            'price'        => 'required|numeric',
            'offer_price'  => 'nullable|numeric',
        ]);

        $variation = ProductVariation::find($request->id);

        if (!$variation) {
            return response()->json(['status' => 404, 'message' => 'Variation not found.']);
        }

        $variation->code         = $request->code;
        $variation->weight       = $request->weight;
        $variation->price        = $request->price;
        $variation->offer_price  = $request->offer_price;
        $variation->save();

        return response()->json(['status' => 200, 'message' => 'Variation updated successfully.']);
    }
    

    public function ProductSkuStatus($id)
    {
        $variation = ProductVariation::find($id);

        if (!$variation) {
            return response()->json([
                'status' => 404,
                'message' => 'Variation not found.',
            ]);
        }

        $variation->status = $variation->status ? 0 : 1;
        $variation->save();

        return response()->json([
            'status' => 200,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function ProductSkuDelete(Request $request)
    {
        $variation = ProductVariation::find($request->id); 

        if (!$variation) {
            return response()->json([
                'status' => 404,
                'message' => 'Product variation not found.',
            ]);
        }

        $variation->delete(); 
        return response()->json([
            'status' => 200,
            'message' => 'Variation deleted successfully.',
        ]);
    }


    public function productSkuListImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('csv_file');
        $extension = $file->getClientOriginalExtension();

        if (in_array($extension, ['xlsx', 'xls'])) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } else {
            // Fallback to CSV
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        }

        $header = array_map('trim', $rows[0]);
        unset($rows[0]);

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            if (count($row) < count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);
           // dd($data);

            $product = Product::where('style_no', $data['product_no'])->first();
            //dd($product);

            if (!$product) {
                $skipped++;
                continue;
            }

            // for duplicate check
            $exists = ProductVariation::where('product_id', $product->id)
                ->where('code', $data['code'])
                ->where('weight', $data['weight'])
                ->exists();

            $duplicateCount = 0;
            if ($exists) {
                $skipped++;
                $duplicateCount++;
                continue;
            }

            $validator = Validator::make($data, [
                'material_code'   => 'required',
                'weight'       => 'required|string|max:255',
                'code'         => 'required|string|max:255',
                'price'        => 'required|numeric',
                'offer_price'  => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $skipped++;
                continue;
            }

            ProductVariation::create([
                'product_id'   => $product->id,
                'weight'       => $data['weight'],
                'code'         => $data['code'],
                'price'        => $data['price'],
                'offer_price'  => $data['offer_price'] ?? null,
                'position'     => 1,
                'stock'        => 0,
                'status'       => 1,
            ]);

            $imported++;
        }

        return redirect()->back()->with('success', "$imported variations imported, $skipped skipped. ($duplicateCount duplicates found)");
    }

    public function productSkuListExport(Request $request){
        $search = $request->input('search');

        $query = ProductVariation::with('product:id,name,style_no');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                ->orWhereHas('product', function($qp) use ($search) {
                    $qp->where('name', 'like', "%{$search}%")
                        ->orWhere('style_no', 'like', "%{$search}%");
                });
            });
        }

        $skus = $query->get();
        // Build spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $headings = [
            'SKU Code',
            'Product Name',
            'Product No',
            'Weight',
            'Position',
            'Price',
            'Offer Price',
            'Status',
        ];

        $sheet->fromArray($headings, null, 'A1');

        // Data
        $rowNum = 2;
        foreach ($skus as $row) {
            $sheet->fromArray([
                $row->code,
                optional($row->product)->name,
                optional($row->product)->style_no,
                $row->weight,
                $row->position,
                $row->price,
                $row->offer_price,
                $row->status ? 'Active' : 'Inactive',
            ], null, 'A'.$rowNum);
            $rowNum++;
        }

        // File name
        $fileName = 'product_sku_list_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        // Output as download
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

}





    // public function productSkuListSyncAll(Request $request)
    // {
    //     $data = (object)[];
    //     // DB::enableQueryLog();
    //     $data->skuCount = ProductColorSize::where('code', '!=', '')->Where('code', '!=', NULL)->count();
    //     // dd(DB::getQUeryLog());

    //     return view('admin.product.product-sku-all', compact('data', 'request'));
    // }

