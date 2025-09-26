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

        if ($request->ajax()) {
            $cc = Product::where('collection_id', $cid)->groupBy('cat_id')->select('cat_id')->with('category')->get();
            return json_encode($cc);
        }

        return view('admin.product.index', compact('data', 'catagories'));
    }

    public function create(Request $request)
    {
        $categories = $this->productRepository->categoryList();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
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

        
            "variations" => "required|array|min:1",
            "variations.*.weight" => "required|string|max:50",
            "variations.*.code" => "required|string|max:100|unique:product_variation,code",
            "variations.*.price" => "required|numeric",
            "variations.*.offer_price" => "nullable|numeric",
            "variations.*.stock" => "required|integer|min:0",
            "variations.*.images.*" => "nullable|image|mimes:jpg,jpeg,png,svg,gif,webp|max:10000000",
        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.product.index')->with('success', 'New Product created, add Product Variation!');

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

    public function edit(Request $request, $id)
    {
        $categories = $this->productRepository->categoryList();
        $data = $this->productRepository->listById($id);
        $images = $this->productRepository->listImagesById($id);

        $product = Product::with('variations.images')->findOrFail($id);

        \DB::statement("SET SQL_MODE=''");

        return view('admin.product.edit', compact('id', 'data', 'categories', 'images','product'));
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
            "offer_price" => "nullable|integer",
            "meta_title" => "nullable|string",
            "meta_desc" => "nullable|string",
            "meta_keyword" => "nullable|string",
            "style_no" => "nullable",
            "image" => "nullable",
            "size_chart_image" => "nullable",
            "product_images" => "nullable|array",
            'gst'=>'nullable|regex:/^[^\+\-\&\%]+$/',
            "pack" => "nullable|string|max:255",

            
            "variations" => "required|array|min:1",
            "variations.*.weight" => "required|string|max:50",
           "variations.*.code" => [
                'required',
                'string',
                'max:100',
                function($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $variationId = $request->input("variations.$index.id") ?? null;

                    $existsQuery = ProductVariation::where('code', $value);
                    if ($variationId) {
                        $existsQuery->where('id', '!=', $variationId);
                    }

                    if ($existsQuery->exists()) {
                        $fail("The $attribute has already been taken.");
                    }
                }
            ],
            "variations.*.price" => "required|numeric",
            "variations.*.offer_price" => "nullable|numeric",
            "variations.*.stock" => "required|integer|min:0",
            "variations.*.images.*" => "nullable|image|mimes:jpg,jpeg,png,svg,gif,webp|max:10000000",
        ]);

        $params = $request->except('_token');
        $storeData = $this->productRepository->update($request->product_id, $params);
       // dd($storeData);
        if ($storeData) {
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
        $orderProducts = OrderProduct::where('product_variation_id', $variation->id)->exists();

        if ($orderProducts) {
            return response()->json([
                'status' => 400,
                'message' => 'This product variation is associated with existing orders and cannot be deleted.',

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
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        }

        $header = array_map('trim', $rows[0]);
        unset($rows[0]);

        $imported = 0;
        $skipped = 0;
        $duplicateCodes = []; 

        foreach ($rows as $index => $row) {
            if (count($row) < count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'material_code' => 'required',
                'weight'        => 'required|string|max:255',
                'code'          => 'required|string|max:255',
                'price'         => 'required|numeric',
                'offer_price'   => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $skipped++;
                continue;
            }

            $product = Product::where('style_no', $data['material_code'])->first();
            if (!$product) {
                $skipped++;
                continue;
            }

            $existsInDb = ProductVariation::where('code', $data['code'])->exists();

            if (in_array($data['code'], $duplicateCodes) || $existsInDb) {
                $duplicateCodes[] = $data['code'];
                $skipped++;
                continue;
            }

            ProductVariation::create([
                'product_id'  => $product->id,
                'weight'      => $data['weight'],
                'code'        => $data['code'],
                'price'       => $data['price'],
                'offer_price' => $data['offer_price'] ?? null,
                'position'    => 1,
                'stock'       => 0,
                'status'      => 1,
            ]);

            $imported++;
        }

        $message = "$imported variations imported, $skipped skipped.";
        if (!empty($duplicateCodes)) {
            $message .= " Duplicate codes found: " . implode(', ', array_unique($duplicateCodes));
        }
        if($imported > 0){
            return redirect()->back()->with('success', $message);
        }else{
            return redirect()->back()->with('failure', $message);
        }
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

