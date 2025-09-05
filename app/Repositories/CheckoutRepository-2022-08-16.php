<?php

namespace App\Repositories;

use App\Interfaces\CheckoutInterface;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Settings;
use App\Models\Collection;
use App\Models\Transaction;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CheckoutRepository implements CheckoutInterface
{
    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function viewCart()
    {
        $data = Cart::where('ip', $this->ip)->get();

        // coupon check
        if (!empty($data[0]->coupon_code_id)) {
            $coupon_code_id = $data[0]->coupon_code_id;
            $coupon_code_end_date = $data[0]->couponDetails->end_date;
            $coupon_code_status = $data[0]->couponDetails->status;
            $coupon_code_max_usage_for_one = $data[0]->couponDetails->max_time_one_can_use;

            // coupon code validity check
            if ($coupon_code_end_date < \Carbon\Carbon::now() || $coupon_code_status == 0) {
                Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
            }

            // coupon code usage check
            if (Auth::guard('web')->user()) {
                $couponUsageCount = CouponUsage::where('user_id', Auth::guard('web')->user()->id)->orWhere('email', Auth::guard('web')->user()->email)->count();
            } else {
                $couponUsageCount = CouponUsage::where('ip', $this->ip)->count();
            }

            if ($couponUsageCount == $coupon_code_max_usage_for_one || $couponUsageCount > $coupon_code_max_usage_for_one) {
                Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
            }
        }

        return $data;
    }

    public function addressData()
    {
        return Address::where('user_id', Auth::guard('web')->user()->id)->get();
    }

    public function create(array $data)
    {
        $collectedData = collect($data);

        DB::beginTransaction();

        try {
            // 1 order
            $order_no = "ONN".mt_rand();
            $newEntry = new Order;
            $newEntry->order_no = $order_no;
            $newEntry->user_id = Auth::guard('web')->user()->id ?? 0;
            $newEntry->ip = $this->ip;
            $newEntry->email = $collectedData['email'];
            $newEntry->mobile = $collectedData['mobile'];
            $newEntry->fname = $collectedData['fname'];
            $newEntry->lname = $collectedData['lname'];
            $newEntry->billing_country = $collectedData['billing_country'];
            $newEntry->billing_address = $collectedData['billing_address'];
            $newEntry->billing_landmark = $collectedData['billing_landmark'];
            $newEntry->billing_city = $collectedData['billing_city'];
            $newEntry->billing_state = $collectedData['billing_state'];
            $newEntry->billing_pin = $collectedData['billing_pin'];

            // shipping & billing address check
            $shippingSameAsBilling = $collectedData['shippingSameAsBilling'] ?? 0;
            $newEntry->shippingSameAsBilling = $shippingSameAsBilling;

            // dd($shippingSameAsBilling);

            if ($shippingSameAsBilling == 0) {
                $newEntry->shipping_country = $collectedData['shipping_country'];
                $newEntry->shipping_address = $collectedData['shipping_address'];
                $newEntry->shipping_landmark = $collectedData['shipping_landmark'];
                $newEntry->shipping_city = $collectedData['shipping_city'];
                $newEntry->shipping_state = $collectedData['shipping_state'];
                $newEntry->shipping_pin = $collectedData['shipping_pin'];
            } else {
                $newEntry->shipping_country = $collectedData['billing_country'];
                $newEntry->shipping_address = $collectedData['billing_address'];
                $newEntry->shipping_landmark = $collectedData['billing_landmark'];
                $newEntry->shipping_city = $collectedData['billing_city'];
                $newEntry->shipping_state = $collectedData['billing_state'];
                $newEntry->shipping_pin = $collectedData['billing_pin'];
            }

            $newEntry->shipping_method = $collectedData['shipping_method'];

            // fetch cart details
            $cartData = Cart::where('ip', $this->ip)->get();
            $subtotal = 0;
            foreach($cartData as $cartValue) {
                $subtotal += $cartValue->offer_price * $cartValue->qty;
            }
            $coupon_code_id = $cartData[0]->coupon_code_id ?? 0;
            $newEntry->coupon_code_id = $coupon_code_id;
            $newEntry->amount = $subtotal;
            $newEntry->shipping_charges = 0;
            $newEntry->tax_amount = 0;
            // $newEntry->discount_amount = 0;
            // $newEntry->coupon_code_id = 0;
            $total = (int) $subtotal;
            if (!empty($coupon_code_id) || $coupon_code_id != 0) {
				$newEntry->discount_amount = $cartData[0]->couponDetails->amount;
				$newEntry->final_amount = $total - (int) $cartData[0]->couponDetails->amount;
				// $newEntry->final_amount = $total;
				$newEntry->save();
            } else {
                $newEntry->discount_amount = 0;
				$newEntry->final_amount = $total;
				$newEntry->save();
            }

            // coupon code usage handler
            if (!empty($coupon_code_id) || $coupon_code_id != 0) {
                $newEntry->discount_amount = $cartData[0]->couponDetails->amount;
                $newEntry->final_amount = $total - (int) $cartData[0]->couponDetails->amount;

                // update coupon code usage
                $couponDetails = Coupon::findOrFail($coupon_code_id);
                $old_no_of_usage = $couponDetails->no_of_usage;
                $new_no_of_usage = $old_no_of_usage + 1;
                $couponDetails->no_of_usage = $new_no_of_usage;
                if ($new_no_of_usage == $couponDetails->max_time_of_use) $couponDetails->status = 0;
                $couponDetails->save();

                $newCouponUsageEntry = new CouponUsage();
                $newCouponUsageEntry->coupon_code_id = $coupon_code_id;
                $newCouponUsageEntry->coupon_code = $couponDetails->coupon_code;
                $newCouponUsageEntry->discount = $cartData[0]->couponDetails->amount;
                $newCouponUsageEntry->total_checkout_amount = $total;
                $newCouponUsageEntry->final_amount = $total - (int) $cartData[0]->couponDetails->amount;
                $newCouponUsageEntry->user_id = Auth::guard('web')->user()->id ?? 0;
                $newCouponUsageEntry->email = $collectedData['email'];
                $newCouponUsageEntry->ip = $this->ip;
                $newCouponUsageEntry->order_id = $newEntry->id;
                $newCouponUsageEntry->usage_time = date('Y-m-d H:i:s');
                $newCouponUsageEntry->save();
            }

            // 2 insert cart data into order products
            $orderProducts = [];
            foreach($cartData as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $newEntry->id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'qty' => $cartValue->qty,
                ];
            }
            $orderProductsNewEntry = OrderProduct::insert($orderProducts);

            // 3 send product details mail
            $email_data = [
                'name' => $collectedData['fname'].' '.$collectedData['lname'],
                'subject' => 'Onn - New Order',
                'email' => $collectedData['email'],
                'orderId' => $newEntry->id,
                'orderNo' => $order_no,
                'orderAmount' => $total,
                'orderProducts' => $orderProducts,
                'blade_file' => 'front/mail/order-confirm',
            ];

            SendMail($email_data);

            // send invoice mail starts
            $invoice_email_data = [
                'name' => $collectedData['fname'].' '.$collectedData['lname'],
                'subject' => 'Onn - Order Invoice',
                'email' => $collectedData['email'],
                'orderId' => $newEntry->id,
                'orderNo' => $order_no,
                'orderAmount' => $total,
                // 'orderProducts' => $orderProducts,
                'blade_file' => 'front/mail/invoice',
            ];
            SendMail($invoice_email_data);

            // send mail starts
            /* $settings = Settings::all();
            $collections = Collection::orderBy('position')->where('status', 1)->get();
            $bookingProductsBody = '';
            $bookingProductsBody .= '<table width="550" border="0" cellspacing="0" cellpadding="0" class="amttable">
                <thead>
                    <tr>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold" width="50">Sl. No.</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold">Particulars</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px;  font-weight:bold">Quantity</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold">Amount</th>
                    </tr>
                </thead>';
            $bookingProductsBody .='<tbody>';

            $i=1;
            foreach($email_data['orderProducts'] as $bookingProduct){
                $bookingProductsBody .= '<tr>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400" width="50">'.$i.'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400">'.$bookingProduct['product_name'].'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px;  font-weight:400">'.$bookingProduct['qty'].'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400">Rs '.$bookingProduct['offer_price']*$bookingProduct['qty'].'</td>
                        </tr>
                    </tbody>';
                $i++;
            }

            $bookingProductsBody .='</tbody></table>';
            $mail_format = "";

            $mail_format .= '<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#f9f9f9; -webkit-text-size-adjust:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f9f9f9" style="margin-top:10px;">
                                <tr>
                                    <td align="center">
                                        <table width="500" border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="padding: 0px 0px 0px 0px;">
                                            <tr>
                                                <td class="td" style="width:550px; min-width:550px; font-size:0pt; line-height:0pt; padding:3px; margin:0; font-weight:normal;">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding:0px 20px 0px 20px; border-bottom: 1px solid #deedf7;">
                                                        <tr>
                                                            <td class="p30-15 tbrr" style="padding: 5px 0px 5px 0px;">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <th class="column-top" width="145" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td class="img m-center" style="font-size:0pt; line-height:0pt; text-align:left; padding:10px 10px 10px 10px"><a href="JavaScript:void(0);" target="_blank"><img src="https://onninternational.com/img/logo.png" style="width: 170px;"></a></td>
                                                                                </tr>
                                                                            </table>
                                                                        </th>
                                                                        <th class="column-empty2" width="1" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;"></th>

                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  bgcolor="#ffffff" style="padding:0px 20px 0px 20px;">
                                                        <tr>
                                                            <td class="p30-15" style="padding: 15px 0px 20px 0px;">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:12px; font-weight:500;">Dear <span> '.$email_data['name'].',<span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:8px;">Congratulations on placing your highly valued order with us.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:24px; text-align:left; padding-bottom:8px;">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <p style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-bottom:8px;">The synopsis of the order is as follows:</p>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>'.$bookingProductsBody.'</tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-bottom:8px;">Your order will be despatched as soon as possible.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-bottom:8px;">In case of any query do get in touch with us <span style="font-weight:bold; text-decoration:underline; color:#cc0000">E-Mail us at '.$settings[7]->content.' or Call us at '.$settings[6]->content.'</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-top:15px;">Assuring you the best of our services</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-top:15px;">Regards </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:left; padding-bottom:8px;">Team ONN</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text pb15" style="color:#2e364b; font-family:Arial, sans-serif; font-size:12px; line-height:20px; text-align:left; padding-bottom:8px; font-weight:bold">Browse Large variety of Products from :';

                                            $displayCollections=  '';
                                            foreach($collections as $collectionKey => $collectionValue) {
                                                $displayCollections .= '
                                                <span>'.$collectionValue->name.'</span>,
                                                ';
                                            }



                                           $mail_format .= $displayCollections.'</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table width="590" border="0" cellspacing="0" cellpadding="0" style="padding:0px; border-top:3px solid #45adff;">
                                                        <tr>
                                                            <td style="width:100%; height:10px; background-color:#1f69a5;"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="p0-15-30" style="padding:15px;">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td class="text-footer1 pb10" style="color:#ababab; font-family:Arial, sans-serif; font-size:12px; line-height:13px; text-align:left; vertical-align:middle; padding-bottom:5px;">Total Comfort &copy; 2021-'.date('Y').'</td>

                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>';

            $headers = '';
            $headers.= "MIME-Version: 1.0\r\n";
            $headers.= "Content-type: text/html; charset=utf-8 \r\n";
            $headers.= "From: support@onninternational.com" . "\r\n";

            $mail_send = mail($email_data['email'], "ONN - Order Confirmation", $mail_format, $headers); */
            // send mail ends


            // send invoice mail starts
            /* $email_data = [
                'name' => $collectedData['fname'].' '.$collectedData['lname'],
                'subject' => 'Onn - Order Invoice',
                'email' => $collectedData['email'],
                'orderNo' => $order_no,
                'blade_file' => 'front/mail/invoice',
            ];
            SendMail($email_data); */

            /* $bookingProductsBody = '';
            $bookingProductsBody .= '<table width="550" border="0" cellspacing="0" cellpadding="0" class="amttable">
                <thead>
                    <tr>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold" width="50">Sl. No.</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold">Particulars</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px;  font-weight:bold">Quantity</th>
                        <th style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:bold">Amount</th>
                    </tr>
                </thead>';
            $bookingProductsBody .='<tbody>';

            $i=1;
            foreach($email_data['orderProducts'] as $bookingProduct){
                $bookingProductsBody .= '<tr>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400" width="50">'.$i.'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400">'.$bookingProduct['product_name'].'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px;  font-weight:400">'.$bookingProduct['qty'].'</td>
                            <td style="color:#000; font-family:Arial, sans-serif; font-size:14px; line-height:20px; text-align:center; padding:4px; font-weight:400">Rs '.$bookingProduct['offer_price']*$bookingProduct['qty'].'</td>
                        </tr>
                    </tbody>';
                $i++;
            }

            $bookingProductsBody .='</tbody></table>'; */
            /* $mail_format = "";
            $invoiceData = \App\Models\Order::findOrFail($newEntry->id);
            $orderedProducts = '';

            foreach ($invoiceData->orderProducts as $productKey => $productVal) {
                $rate = 0;
                $tax = 0;
                $gst = 5;
                $amount = 0;

                $amount = $productVal->offer_price * $productVal->qty;
                $amountShow = sprintf("%.2f", $amount);

                $gstShow = sprintf("%.3f", $gst);
                $taxInTotalAmount = ($gst / 100) * $amount;
                $withoutTaxAmount = $amount - $taxInTotalAmount;

                $orderedProducts .= '
                <tr>
                    <td align="center">1</td>
                    <td><strong>'. $productVal->product_name .'</strong></td>
                    <td>GENX_GUSTO_BRF_OE_AST_7 5_3PC</td>
                    <td align="center">'. $productVal->qty .'</td>
                    <td align="right">'. sprintf('%.2f', $productVal->offer_price) .'</td>
                    <td align="right">'. $withoutTaxAmount .'</td>
                    <td align="right">'. $taxInTotalAmount .'<br />('. $gstShow .'%)</td>
                    <td align="right">'. $amountShow .'</td>
                </tr>';
            }

            $mail_format .= '<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#f9f9f9; -webkit-text-size-adjust:none;">
                <table border="1" class="table-bordered" style="width: 100%; border-collapse: collapse;"
                cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table border="1" style="width: 100%; border-collapse: collapse;"
                            class="table-bordered" cellpadding="10" cellspacing="0">
                            <tr>
                                <td style="width: 35%;" rowspan="2">
                                    <p style="margin: 0">Sender</p>
                                    <p style="margin: 0;"><strong>cozyworld</strong><br />C-158, Amar Gyan Industrial Estate, Khopat,Thane Maharashtra - 400601 Maharashtra (27) ,India</p>
                                    <p style="margin: 0;">Ph No: 9007015173</p>
                                    <p style="margin: 0;">GSTIN:</p>
                                </td>
                                <td style="width: 35%;">
                                    <p style="margin: 0;">Invoice
                                        Code:<br /><strong>I1923CO000000429</strong></p>
                                </td>
                                <td style="width: 30%;">
                                    <p style="margin: 0;">Invoice Date:<br /><strong>'.date('j-M-Y', strtotime($invoiceData->created_at)).'</strong>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="margin: 0;">Order No:
                                        '.$invoiceData->order_no.'</p>
                                    <p style="margin: 0;">Order Date: '.date('j-M-Y', strtotime($invoiceData->created_at)).'</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Portal: <strong>MYNTRA_PPMP</strong></p>
                                    <p style="margin: 0;">Payment Mode</p>
                                    <p style="margin: 0;"><strong>PREPAID</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="margin: 0;">Bill To: <br />
                                        <strong>'.$invoiceData->fname.' '.$invoiceData->lname.'</strong>
                                        <br />'.$invoiceData->billing_address.', '.$invoiceData->billing_landmark.', '.$invoiceData->billing_pin.', '.$invoiceData->billing_city.', '.$invoiceData->billing_state.', '.$invoiceData->billing_country.'</p>
                                    <p style="margin: 0;">T : '.$invoiceData->mobile.'</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Ship To: <br />
                                        <strong>'.$invoiceData->fname.' '.$invoiceData->lname.'</strong>
                                        <br />'.$invoiceData->shipping_address.', '.$invoiceData->shipping_landmark.', '.$invoiceData->shipping_pin.', '.$invoiceData->shipping_city.', '.$invoiceData->shipping_state.', '.$invoiceData->shipping_country.'</p>
                                    <p style="margin: 0;">T : '.$invoiceData->mobile.'</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Dispatch Through<br /><strong>EK</strong><br />AWB
                                        No<br /> MYNP0032032743</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="1" style="width: 100%; border-collapse: collapse;"
                            class="table-bordered" cellpadding="10" cellspacing="0">
                            <tr>
                                <th align="center">SI No.</th>
                                <th align="center">Descriptions of Goods</th>
                                <th align="center">Part No</th>
                                <th align="center">Qty</th>
                                <th align="center">Rate</th>
                                <th align="center">Taxable Value<br />(INR)</th>
                                <th align="center">IGST (INR)</th>
                                <th align="center">Amount (INR)</th>
                            </tr>
                            '.$orderedProducts.'
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" style="width: 100%; border-collapse: collapse;"
                            class="" cellpadding="10" cellspacing="0">
                            <tr>
                                <td valign="top">
                                    <p style="margin: 0;">Amount Chargeable (in words)<br /><strong>INR '. amountInWords($invoiceData->final_amount) .' Only Tax is payable on reverse charge basis: No</strong></p>
                                </td>
                                <td align="right" valign="top">
                                    <h4 style="margin: 0; font-size: 14px;">E. & O.E</h4>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <p style="margin: 0;"><u>Declaration</u></p>
                                    <p style="margin: 0;">1. All claims, if any, for shortages or damages
                                        must be reported to customer service on the day of delivery through
                                        the contact us page on the web store 2. All Disputes are subject to
                                        Maharashtra (27) jurisdiction only.</p>
                                </td>
                                <td align="center"
                                    style="width: 50%; border-top: 1px solid #000; border-left: 1px solid #000;">
                                    <h3>ONN International</h3>
                                    <h3>Authorised Signatory</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" style="width: 100%; border-collapse: collapse;"
                            class="" cellpadding="10" cellspacing="0">
                            <tr>
                                <td style="width: 49%;">
                                    <p style="margin: 0;"><strong>Bill By</strong>
                                </td>
                                <td style="width: 2%;" align="center">:</td>
                                <td style="width: 49%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </body>';

            $invoiceHeaders = '';
            $invoiceHeaders.= "MIME-Version: 1.0\r\n";
            $invoiceHeaders.= "Content-type: text/html; charset=utf-8 \r\n";
            $invoiceHeaders.= "From: support@onninternational.com" . "\r\n";

            $invoiceMail_send = mail($email_data['email'], "ONN - Order Invoice", $mail_format, $invoiceHeaders); */
            // send invoice mail ends



			// Shiprocket
            $this->shiprocket($newEntry,$cartData);
			
			// Unicommerce

            // 4 remove cart data
            $emptyCart = Cart::where('ip', $this->ip)->delete();

            // 5 online payment
            if (isset($data['razorpay_payment_id'])) {
                $txnData = new Transaction();
                $txnData->user_id = Auth::guard('web')->user()->id ?? 0;
                $txnData->order_id = $newEntry->id;
                $txnData->transaction = 'TXN_'.strtoupper(Str::random(20));
                $txnData->online_payment_id = $collectedData['razorpay_payment_id'];
                $txnData->amount = $total;
                $txnData->currency = "INR";
                $txnData->method = "";
                $txnData->description = "";
                $txnData->bank = "";
                $txnData->upi = "";
                $txnData->save();
            }

            DB::commit();
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            dd($th);
            DB::rollback();
            return false;
        }
    }

    public function shiprocket($booking,$items){

        $logindetails = $this->shiprocketlogin();
        $logindetails = json_decode($logindetails);

        $dt = date('Y-m-d H:i:s');

        $pushdata = array();

        foreach($items as $n){
            $data['name'] = $n->product_name;
            $data['sku'] = $n->product_style_no;
            $data['units'] = $n->qty;
            $data['selling_price'] = $n->offer_price;

            array_push($pushdata, $data);
        }

        //$name =  $this->split_name($booking->name);
        $jsondata['order_id'] = $booking->order_no;
        $jsondata['order_date'] = date('Y-m-d H:i:s');
        $jsondata['channel_id'] = '2865152';
        $jsondata['pickup_location'] = 'Lux Dankuni';
        $jsondata['billing_customer_name'] = $booking->fname;
        $jsondata['billing_last_name'] = $booking->lname;
        $jsondata['billing_address'] = $booking->billing_address;
        $jsondata['billing_address_2'] = $booking->billing_landmark;
        $jsondata['billing_city'] = $booking->billing_city;
        $jsondata['billing_pincode'] = $booking->billing_pin;
        $jsondata['billing_state'] = $booking->billing_state;
        $jsondata['billing_country'] = $booking->billing_country;
        $jsondata['billing_email'] = $booking->email;
        $jsondata['billing_phone'] = $booking->mobile;
        $jsondata['shipping_is_billing'] = true;
        $jsondata['shipping_customer_name'] = '';
        $jsondata['shipping_last_name'] = '';
        $jsondata['shipping_address'] = '';
        $jsondata['shipping_address_2'] = '';
        $jsondata['shipping_city'] = '';
        $jsondata['shipping_pincode'] = '';
        $jsondata['shipping_country'] = '';
        $jsondata['shipping_state'] = '';
        $jsondata['shipping_email'] = '';
        $jsondata['shipping_phone'] = '';
        $jsondata['order_items'] = $pushdata;

        $payment_method = '';
        if($booking->payment_method=='online_payment') {
            $payment_method = "Prepaid";
        } else{
			$payment_method = "Cod";
		}

        $jsondata['payment_method'] = $payment_method;
        $jsondata['shipping_charges'] = $booking->shipping_charge;
        $jsondata['total_discount'] = $booking->discount_amount;
        $jsondata['sub_total'] = $booking->final_amount;
        $jsondata['length'] = 10;
        $jsondata['breadth'] = 1;
        $jsondata['height'] = 10;
        $jsondata['weight'] = 5;

        $token = $logindetails->token;

        // echo json_encode($jsondata);

        // die();

        //echo $token;

        $url = 'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc';

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer ". $token
        );
        //  echo '<pre>';
        //  print_r($headers);
        //  die();
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsondata));
        // Execute post
        $result = curl_exec($ch);

        //echo "result : ".$result;
        //die();

        curl_close($ch);

        return $result;

        /*return response()
            ->json(["jsondata"=>$jsondata]);*/
    }

    public function shiprocketlogin(){

        $headers = array(
            'Content-Type: application/json'
        );

        $jsondata['email'] = "suvajit.bardhan@onenesstechs.in";
        $jsondata['password'] = "Welcome#2022";

        $url = 'https://apiv2.shiprocket.in/v1/external/auth/login';

        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsondata));
        // Execute post
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

	public function unicommerceLogin(){
        $headers = array(
            'Content-Type: application/json'
        );

        $username = "rohit@onenesstechs.in";
        $password = "q%23393KHVqRBPDTE";

        $url = "https://cozyworld.unicommerce.com/oauth/token?grant_type=password&client_id=my-trusted-client&username=".$username."&password=".$password;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        // var_dump($resp);
        return $resp;
    }

    public function split_name($name) {
		$name = trim($name);
		$last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
		$first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
		return array($first_name, $last_name);
    }
}
