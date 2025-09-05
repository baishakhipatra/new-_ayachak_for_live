<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // private OrderInterface $orderRepository;

    public function __construct(OrderInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        // dd($request->all());
        if (!empty($request->status)) {
            if (!empty($request->term)) {
                $data = $this->orderRepository->searchOrder($request->term,$request->from_date, $request->to_date, $request->payment_type,$request->status);
            } else {
                $data = $this->orderRepository->listByStatus($request->status);
            }
        } else {
            if(!empty($request->term) || !empty($request->from_date) || !empty($request->to_date) || !empty($request->payment_type)) {
                // dd('here');
                // $data = $this->orderRepository->searchOrder($request->term ?? '',$request->from_date ?? '', $request->to_date ?? '');
                $data = $this->orderRepository->searchOrder($request->term, $request->from_date, $request->to_date, $request->payment_type,'');
            } else {
                $data = $this->orderRepository->listAll();
            }
        }
        return view('admin.order.index', compact('data'));
    }

    public function indexStatus(Request $request, $status)
    {
        $data = $this->orderRepository->listByStatus($status);
        return view('admin.order.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->orderRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.order.index');
        } else {
            return redirect()->route('admin.order.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->orderRepository->listById($id);
        return view('admin.order.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->orderRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.order.index');
        } else {
            return redirect()->route('admin.order.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id, $status)
    {
        $storeData = $this->orderRepository->toggle($id, $status);

        if ($storeData) {
            return redirect()->back();
        } else {
            return redirect()->route('admin.order.index');
        }
    }

    public function statusPost(Request $request)
    {
        // order update
        $updatedEntry = Order::findOrFail($request->id);
        $updatedEntry->status = $request->status;
        $updatedEntry->save();

        DB::table('order_products')->where('order_id', $request->id)->update([
            'status' => $request->status
        ]);

        // dd($updatedEntry);

        // send email
        // fetching ordered products
        $orderedProducts = OrderProduct::where('order_id', $updatedEntry->id)->get()->toArray();

        switch ($updatedEntry->status) {
            case 1:
                $statusTitle = 'New';
                $statusDesc = 'We are currently processing your order';
                break;
            case 2:
                $statusTitle = 'Confirmed';
                $statusDesc = 'Your order is confirmed';
                break;
            case 3:
                $statusTitle = 'Shipped';
                $statusDesc = 'Your order is Shipped. It will reach you soon';
                break;
            case 4:
                $statusTitle = 'Delivered';
                $statusDesc = 'Your order is delivered';
                break;
            case 5:
                $statusTitle = 'Cancelled';
                $statusDesc = 'Your order is cancelled';
                break;
            default:
                $statusTitle = 'New';
                $statusDesc = 'We are currently processing your order';
                break;
        }

        $email_data = [
            'name' => $updatedEntry->fname.' '.$updatedEntry->lname,
            'subject' => 'Onn - Order update for #'.$updatedEntry->order_no,
            'email' => $updatedEntry->email,
            'orderId' => $updatedEntry->id,
            'orderNo' => $updatedEntry->order_no,
            'orderAmount' => $updatedEntry->final_amount,
            'status' => $updatedEntry->status,
            'statusTitle' => $statusTitle,
            'statusDesc' => $statusDesc,
            'orderProducts' => $orderedProducts,
            'blade_file' => 'front/mail/order-update',
        ];

        SendMail($email_data);

        if ($updatedEntry) {
            return response()->json(['error' => false, 'message' => 'Order status updated']);
        } else {
            return response()->json(['error' => true, 'message' => 'Something happened']);
        }
    }

    public function orderProductStatus(Request $request)
    {
        $statusUpdate = DB::table('order_products')->where('id', $request->id)->update([
            'status' => $request->status
        ]);

        // send email
        // fetching ordered products
        $orderedProducts = OrderProduct::findOrFail($request->id);

        switch ($request->status) {
            case 1:
                $statusTitle = 'New';
                $statusDesc = 'We are currently processing your order';
                break;
            case 2:
                $statusTitle = 'Confirmed';
                $statusDesc = 'Your order is confirmed';
                break;
            case 3:
                $statusTitle = 'Shipped';
                $statusDesc = 'Your order is Shipped. It will reach you soon';
                break;
            case 4:
                $statusTitle = 'Delivered';
                $statusDesc = 'Your order is delivered';
                break;
            case 5:
                $statusTitle = 'Cancelled';
                $statusDesc = 'Your order is cancelled';
                break;
            case 6:
                $statusTitle = 'Return request';
                $statusDesc = 'You have requested return for the product';
                break;
            case 7:
                $statusTitle = 'Return approved';
                $statusDesc = 'You return request is approved';
                break;
            case 8:
                $statusTitle = 'Return declined';
                $statusDesc = 'You return request is declined';
                break;
            case 9:
                $statusTitle = 'Products Returned';
                $statusDesc = 'You have returned old products';
                break;
            case 10:
                $statusTitle = 'Products Received';
                $statusDesc = 'Your returned products are received';
                break;
            case 11:
                $statusTitle = 'Products Shipped';
                $statusDesc = 'Your new products are shipped';
                break;
            case 12:
                $statusTitle = 'Products Delivered';
                $statusDesc = 'Your new products are delivered';
                break;
            default:
                $statusTitle = 'New';
                $statusDesc = 'We are currently processing your order';
                break;
        }

        $email_data = [
            'name' => $orderedProducts->orderDetails->fname.' '.$orderedProducts->orderDetails->lname,
            'subject' => 'Onn - Order update for #'.$orderedProducts->orderDetails->order_no,
            'email' => $orderedProducts->orderDetails->email,
            'orderId' => $orderedProducts->orderDetails->id,
            'orderNo' => $orderedProducts->orderDetails->order_no,
            'orderAmount' => $orderedProducts->orderDetails->final_amount,
            'status' => $orderedProducts->orderDetails->status,
            'statusTitle' => $statusTitle,
            'statusDesc' => $statusDesc,
            'orderProducts' => $orderedProducts,
            'blade_file' => 'front/mail/order-update',
        ];

        // dd($email_data);

        SendMail($email_data);

        if ($statusUpdate) {
            return response()->json(['error' => false, 'message' => 'Order status updated']);
        } else {
            return response()->json(['error' => true, 'message' => 'Something happened']);
        }
    }

    public function invoice(Request $request, $id)
    {
        $data = $this->orderRepository->listById($id);
        return view('admin.order.invoice', compact('data'));
    }

	public function exportAll(Request $request)
    {
        // dd('test working');

        $data = DB::select("SELECT op.*, o.fname, o.lname, o.order_no, o.email, o.mobile, o.shipping_charges, o.final_amount, o.billing_address, o.billing_landmark, o.billing_country, o.billing_city, o.billing_pin, o.shipping_address, o.shipping_landmark, o.shipping_country, o.shipping_state, o.shipping_city, o.shipping_pin, o.created_at AS order_created_at
        FROM order_products AS op 
        INNER JOIN orders AS o 
        ON o.id = op.order_id 
        ORDER BY op.id DESC");

        // dd($data);

        if(count($data) > 0) {
            $delimiter = ",";
            $filename = "onninternational-all-orders-".date('Y-m-d').".csv";

            // Create a file pointer 
            $f = fopen('php://memory', 'w');

            // Set column headers 
            $fields = array('SR', 'ORDER NUMBER', 'USER NAME', 'EMAIL', 'MOBILE NUMBER', 
            'PRODUCT', 'SKU CODE', 'QUANTITY', 'PRICE', 'CGST', 'SGST', 'IGST', 
            'STATUS', 
            'ORDER SHIPPING CHARGE', 'ORDER TOTAL AMOUNT', 
            'BILLING ADDRESS', 'BILLING LANDMARK', 'BILLING COUNTRY', 'BILLING STATE', 'BILLING CITY', 'BILLING PINCODE', 
            'SHIPPING ADDRESS', 'SHIPPING LANDMARK', 'SHIPPING COUNTRY', 'SHIPPING STATE', 'SHIPPING CITY', 'SHIPPING PINCODE', 
            'DATETIME');
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {

                // dd($row);

                $f_username = $row->fname ?? '';
                $l_username = $row->lname ?? '';

                switch ($row->status) {
                    case 1:
                        $statusTitle = 'New';
                        $statusDesc = 'We are currently processing your order';
                        break;
                    case 2:
                        $statusTitle = 'Confirmed';
                        break;
                    case 3:
                        $statusTitle = 'Shipped';
                        $statusDesc = 'Your order is Shipped. It will reach you soon';
                        break;
                    case 4:
                        $statusTitle = 'Delivered';
                        $statusDesc = 'Your order is delivered';
                        break;
                    case 5:
                        $statusTitle = 'Cancelled';
                        $statusDesc = 'Your order is cancelled';
                        break;
                    case 6:
                        $statusTitle = 'Return request';
                        $statusDesc = 'You have requested return for the product';
                        break;
                    case 7:
                        $statusTitle = 'Return approved';
                        $statusDesc = 'You return request is approved';
                        break;
                    case 8:
                        $statusTitle = 'Return declined';
                        $statusDesc = 'You return request is declined';
                        break;
                    case 9:
                        $statusTitle = 'Products Returned';
                        $statusDesc = 'You have returned old products';
                        break;
                    case 10:
                        $statusTitle = 'Products Received';
                        $statusDesc = 'Your returned products are received';
                        break;
                    case 11:
                        $statusTitle = 'Products Shipped';
                        $statusDesc = 'Your new products are shipped';
                        break;
                    case 12:
                        $statusTitle = 'Products Delivered';
                        $statusDesc = 'Your new products are delivered';
                        break;
                    default:
                        $statusTitle = 'New';
                        $statusDesc = 'We are currently processing your order';
                        break;
                }

                // gst calculation
                $cgstAmount = $sgstAmount = $igstAmount = $gst = $amount = 0;

                $amount = $row->offer_price * $row->qty;
                if($amount <= 1000) $gst = 5;
                else $gst = 12;

                // $amountShow = sprintf("%.2f", $amount);
                $billing_state = $row->billing_state ?? '';
                $taxInTotalAmount = sprintf("%.2f", ($gst / (100 + $gst)) * $amount);

                if(!empty($billing_state)) $cgstAmount = cgstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');
                if(!empty($billing_state)) $sgstAmount = sgstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');
                if(!empty($billing_state)) $igstAmount = igstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');

                $lineData = array(
                    $count,
                    $row->order_no ?? '',
                    $f_username.' '.$l_username,
                    $row->email ?? '',
                    $row->mobile ?? '',
                    $row->product_name ?? '',
                    $row->sku_code ?? '',
                    $row->qty ?? '',
                    $row->offer_price ?? '',
                    $cgstAmount,
                    $sgstAmount,
                    $igstAmount,
                    $statusTitle,
                    $row->shipping_charges ?? '',
                    $row->final_amount ?? '',
                    $row->billing_address ?? '',
                    $row->billing_landmark ?? '',
                    $row->billing_country ?? '',
                    $billing_state ?? '',
                    $row->billing_city ?? '',
                    $row->billing_pin ?? '',
                    $row->shipping_address ?? '',
                    $row->shipping_landmark ?? '',
                    $row->shipping_country ?? '',
                    $row->shipping_state ?? '',
                    $row->shipping_city ?? '',
                    $row->shipping_pin ?? '',
                    $row->order_created_at ?? ''
                );

                fputcsv($f, $lineData, $delimiter);

                $count++;
            }

            // Move back to beginning of file
            fseek($f, 0);

            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }




        /*
        $data = OrderProduct::with('orderDetails', 'productDetails', 'productVariationDetails')->latest('id')->get();

        // dd($data[0]->orderDetails->id);

        if (count($data) > 0) {
            $delimiter = ",";
            $filename = "onninternational-all-orders-".date('Y-m-d').".csv";

            // Create a file pointer 
            $f = fopen('php://memory', 'w');

            // Set column headers 
            $fields = array('SR', 'ORDER NUMBER', 'USER NAME', 'EMAIL', 'MOBILE NUMBER', 
            'PRODUCT', 'SKU CODE', 'STYLE NUMBER', 'COLOR', 'SIZE', 'QUANTITY', 'PRICE', 'CGST', 'SGST', 'IGST',
            'STATUS', 
            'ORDER SHIPPING CHARGE', 'ORDER TOTAL AMOUNT', 
            'BILLING ADDRESS', 'BILLING LANDMARK', 'BILLING COUNTRY', 'BILLING STATE', 'BILLING CITY', 'BILLING PINCODE', 
            'SHIPPING ADDRESS', 'SHIPPING LANDMARK', 'SHIPPING COUNTRY', 'SHIPPING STATE', 'SHIPPING CITY', 'SHIPPING PINCODE', 
            'DATETIME');
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {

                // dd($row['orderDetails']);

                // dd(date('Y-m-d H:i:s', strtotime($row['orderDetails']['created_at'])));

                // $datetime = $row['orderDetails']['created_at'] ? date('Y-m-d H:i:s', strtotime($row['orderDetails']['created_at'])) : '';
                // $datetime = (string) $datetime;
                // dd($row['orderDetails']['shipping_pin']);
                // $datetime = $row['orderDetails']['created_at'] ? $row['orderDetails']['created_at'] : '';
                $datetime = '';
                // $datetime = $row['orderDetails']['created_at']->toDateTimeString() ?? '';
                // dd($datetime);
                $f_username = $row['orderDetails']['fname'] ?? '';
                $l_username = $row['orderDetails']['lname'] ?? '';

                switch ($row['status']) {
                    case 1:
                        $statusTitle = 'New';
                        $statusDesc = 'We are currently processing your order';
                        break;
                    case 2:
                        $statusTitle = 'Confirmed';
                        $statusDesc = 'Your order is confirmed';
                        break;
                    case 3:
                        $statusTitle = 'Shipped';
                        $statusDesc = 'Your order is Shipped. It will reach you soon';
                        break;
                    case 4:
                        $statusTitle = 'Delivered';
                        $statusDesc = 'Your order is delivered';
                        break;
                    case 5:
                        $statusTitle = 'Cancelled';
                        $statusDesc = 'Your order is cancelled';
                        break;
                    case 6:
                        $statusTitle = 'Return request';
                        $statusDesc = 'You have requested return for the product';
                        break;
                    case 7:
                        $statusTitle = 'Return approved';
                        $statusDesc = 'You return request is approved';
                        break;
                    case 8:
                        $statusTitle = 'Return declined';
                        $statusDesc = 'You return request is declined';
                        break;
                    case 9:
                        $statusTitle = 'Products Returned';
                        $statusDesc = 'You have returned old products';
                        break;
                    case 10:
                        $statusTitle = 'Products Received';
                        $statusDesc = 'Your returned products are received';
                        break;
                    case 11:
                        $statusTitle = 'Products Shipped';
                        $statusDesc = 'Your new products are shipped';
                        break;
                    case 12:
                        $statusTitle = 'Products Delivered';
                        $statusDesc = 'Your new products are delivered';
                        break;
                    default:
                        $statusTitle = 'New';
                        $statusDesc = 'We are currently processing your order';
                        break;
                }

                // gst calculation
                $cgstAmount = $sgstAmount = $igstAmount = $gst = $amount = 0;

                $amount = $row['offer_price'] * $row['qty'];
                if($amount <= 1000) $gst = 5;
                else $gst = 12;

                // $amountShow = sprintf("%.2f", $amount);
                $billing_state = $row['orderDetails']['billing_state'] ?? '';
                $taxInTotalAmount = sprintf("%.2f", ($gst / (100 + $gst)) * $amount);

                if(!empty($billing_state)) $cgstAmount = cgstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');
                if(!empty($billing_state)) $sgstAmount = sgstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');
                if(!empty($billing_state)) $igstAmount = igstCalc($billing_state, $taxInTotalAmount, $gst, 'no-percentage');

                // dd($row->created_at);

                // order datetime
                // $order_date = DB::table('orders')->select('created_at')->where('id', $row->order_id)->first();
                // dd($order_date->created_at);
                // $datetime = $order_date->created_at;

                $lineData = array(
                    $count,
                    $row['orderDetails']['order_no'] ?? '',
                    $f_username.' '.$l_username,
                    $row['orderDetails']['email'] ?? '',
                    $row['orderDetails']['mobile'] ?? '',
                    $row['product_name'] ?? '',
                    $row['sku_code'] ?? '',
                    $row['productDetails']['style_no'] ?? '',
                    $row['productVariationDetails']['colorDetails']['name'] ?? '',
                    $row['productVariationDetails']['sizeDetails']['name'] ?? '',
                    $row['qty'] ?? '',
                    $row['offer_price'] ?? '',
                    $cgstAmount,
                    $sgstAmount,
                    $igstAmount,
                    $statusTitle,
                    $row['orderDetails']['shipping_charges'] ?? '',
                    $row['orderDetails']['final_amount'] ?? '',
                    $row['orderDetails']['billing_address'] ?? '',
                    $row['orderDetails']['billing_landmark'] ?? '',
                    $row['orderDetails']['billing_country'] ?? '',
                    $billing_state ?? '',
                    $row['orderDetails']['billing_city'] ?? '',
                    $row['orderDetails']['billing_pin'] ?? '',
                    $row['orderDetails']['shipping_address'] ?? '',
                    $row['orderDetails']['shipping_landmark'] ?? '',
                    $row['orderDetails']['shipping_country'] ?? '',
                    $row['orderDetails']['shipping_state'] ?? '',
                    $row['orderDetails']['shipping_city'] ?? '',
                    $row['orderDetails']['shipping_pin'] ?? '',
                    $datetime
                );

                fputcsv($f, $lineData, $delimiter);

                $count++;
            }

            // Move back to beginning of file
            fseek($f, 0);

            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
        */
    }

    // public function destroy(Request $request, $id)
    // {
    //     $this->orderRepository->delete($id);

    //     return redirect()->route('admin.order.index');
    // }

    public function report(Request $request)
    {
        $data = (object) [];

        $from = $request->from_date ? $request->from_date : date('Y-m-01');
        $to = $request->to_date ? $request->to_date : date('Y-m-d');
        $collection = (!empty($request->collection) && $request->collection != 'all') ? $request->collection : '';
        $product = $request->product ?? '';
        $payment_type = $request->payment_type ?? '';
        $term = $request->term ?? '';



        $data->collections = Collection::where('status', 1)->orderBy('position')->get();
        // $data->categories = Category::where('status', 1)->orderBy('position')->get();
        $data->products = Product::where('status', 1)->orderBy('style_no')->get();
        $data->products = ProductColorSize::where('status', 1)->where('code', '!=', ' ')->orderBy('code')->get();

        // all order products
        $query1 = OrderProduct::join('orders', 'orders.id', 'order_products.order_id')
        ->where('orders.created_at', '>=', $from)
        ->where('orders.created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')))
        ->where('orders.is_live_order', 1);

        $query1->when($collection, function($query1) use ($collection) {
            $query1->join('products', 'products.id', 'order_products.product_id')
            ->where('products.collection_id', $collection);
        });
        $query1->when($product, function($query1) use ($product) {
            $query1->where('order_products.sku_code', $product);
        });
        $query1->when($payment_type, function($query1) use ($payment_type) {
            $query1->where('orders.payment_method', $payment_type);
        });
        $query1->when($term, function($query1) use ($term) {
            $query1->Where('orders.order_no', 'like', '%' . $term . '%');
        });

        $data->all_orders = $query1->latest('orders.id')
        ->get();

        // $data->all_orders = OrderProduct::select('*')
        // ->join('orders', 'orders.id', 'order_products.order_id')
        // ->where('orders.created_at', '>=', $from)
        // ->where('orders.created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')))
        // ->where('orders.is_live_order', 1)
        // ->latest('orders.id')
        // ->get();

        // total order amount calculation
        /*
        $query1 = Order::selectRaw('SUM(orders.final_amount) AS total')->join('order_products', 'orders.id', 'order_products.order_id')
        ->where('orders.created_at', '>=', $from)
        ->where('orders.created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')))
        ->where('orders.is_live_order', 1);

        $query1->when($collection, function($query1) use ($collection) {
            $query1->join('products', 'products.id', 'order_products.product_id')
            ->where('products.collection_id', $collection);
        });
        $query1->when($product, function($query1) use ($product) {
            $query1->where('order_products.sku_code', $product);
        });
        $query1->when($payment_type, function($query1) use ($payment_type) {
            $query1->where('orders.payment_method', $payment_type);
        });
        $query1->when($term, function($query1) use ($term) {
            $query1->Where('orders.order_no', 'like', '%' . $term . '%');
        });

        $data->all_orders_total_amount = $query1->first();
        */

        // dd($data->all_orders_total_amount);

        // $data->all_orders_total_amount = Order::selectRaw('SUM(final_amount) AS total')
        // ->where('created_at', '>=', $from)
        // ->where('created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')))
        // ->where('is_live_order', 1)
        // ->first();



        // daily order
        /*
        $data->daily_order = Order::selectRaw('SUM(final_amount) AS daily_order')
        ->where('created_at', '=', date('Y-m-d'))
        ->where('is_live_order', 1)
        ->first();

        // weekly order
        $data->weekStartsFrom = week_range(date('Y-m-d'))[0];
        $data->weekEndsIn = week_range(date('Y-m-d'))[1];

        $data->weekly_order = Order::selectRaw('SUM(final_amount) AS weekly_order')
        ->where('created_at', '>=', $data->weekStartsFrom)
        ->where('created_at', '<=', $data->weekEndsIn)
        ->where('is_live_order', 1)
        ->first();

        // monthly order
        $data->monthly_order = Order::selectRaw('SUM(final_amount) AS monthly_order')
        ->where('created_at', '>=', $from)
        ->where('created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')))
        ->where('is_live_order', 1)
        ->first();

        // total order
        $data->total_order = Order::selectRaw('SUM(final_amount) AS total_order')
        ->where('is_live_order', 1)
        ->first();

        $data->last_order_date = Order::select('created_at')
        ->latest('id')
        ->first();

        $data->first_order_date = Order::select('created_at')
        ->first();
        */

        /*
        $query = Order::query();

        $query->when($term, function($query) use ($term){
            $query->where('fname', 'like', '%' . $term . '%')
            ->orWhere('lname', 'like', '%' . $term . '%')
            ->orWhere('order_no', 'like', '%' . $term . '%');
            // ->orWhere('amount', 'like', '%' . $term . '%');
        });
        $query->when($from, function($query) use ($from){
            $query->where('created_at', '>=', $from);
        });
        $query->when($to, function($query) use ($to){
            $query->where('created_at', '<=', date('Y-m-d', strtotime($to.'+1 day')));
        });
        $query->when($ptype, function ($query) use ($ptype) {
            $query->where('payment_method', '=', $ptype);
        });

        $data->orders = $query->latest('id')->get();
        */

        return view('admin.order.report', compact('data'));
    }

    public function type(Request $request, $id, $type)
    {
        // $storeData = $this->orderRepository->toggle($id, $type);
        $updatedEntry = Order::findOrFail($id);
        $updatedEntry->is_live_order = $type;
        $updatedEntry->save();

        if ($updatedEntry) {
            return response()->json(['error' => false, 'message' => 'Order status updated']);
            // return redirect()->back()->with('success', 'Order status updated');
        } else {
            return response()->json(['error' => true, 'message' => 'Something happened']);
        }
    }
}
