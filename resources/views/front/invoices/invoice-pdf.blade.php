<!DOCTYPE html>
<html lang="en">
<head>
  <title>Invoice #{{ $order->order_no }}</title>
  <meta charset="utf-8">
</head>
<body>
<style></style>

<table style="background-color: #FFF5F7; width:600px; margin: auto;">
    <tr>
        <td style="text-align: center; padding:20px 0">
            <img src="{{public_path('assets/images/logo.png')}}" alt="" style="width: 80px;">
        </td>
    </tr>
    <tr>
        <td>
            <table style="width:510px; margin-left:auto; margin-right:auto; margin-bottom: 45px; background-color: #fff; padding-bottom: 14px;">
                <tr>
                    <td style="background-color:#D0217C; color:#fff; padding: 0 30px; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                        <h2 style="color:#fff; font-size: 26px; font-weight: 600;">Thank you for your order</h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:20px; padding-right:20px;">
                        <p style="font-weight: 300; line-height: 20px;">Hi {{ ucwords($order->fname . ' ' . $order->lname) }},</p>
                        <p style="font-weight: 300; line-height: 20px;">Just to let you know — we've received your order #{{ $order->order_no }}, and it is now being processed:</p>
                        <p style="font-weight: 300; line-height: 20px;">
                            Thank you for choosing Us!  We’ve received your order, #{{ $order->order_no }}, and our team is now processing everything to ensure you receive your order. 
                            If you have any questions or need support, don’t hesitate to reach out. We're here every step of the way to help you achieve your goals.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:20px; padding-right:20px;">
                        <h3 style="color:#D0217C;">Order Summery</h3>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:20px; padding-right:20px;">
                        <table style="width:100%;">
                            @foreach($order->orderProducts as $item)
                                <tr>
                                    <td style="width:60px; vertical-align: top; padding: 8px;">
                                         <img src="{{ $item->productDetails->image ? public_path($item->productDetails->image) : public_path('assets/images/placeholder-product.jpg') }}" 
                                            style="width:60px;">
                                    </td>
                                    <td style=" vertical-align: top; padding: 8px;">
                                        <h4 style="color:#D0217C; margin-bottom: 2px; margin-top: 0;">{{ $item->productDetails->name }}  <span>x {{ $item->qty }}</span></h4>
                                        <span style="font-size: 13px; display: block; color:#383838; margin-bottom: 2px;">Category:  {{ $item->productDetails->category->name ?? 'N/A' }}</span>
                                        @if(!empty($item->productDetails->variation->weight))
                                            <span style="font-size: 13px; display: block; color:#383838; margin-bottom: 2px;">Weight: {{ $item->productDetails->variation->weight }}</span>
                                        @endif
                                    </td>
                                    <td style=" vertical-align: top; padding: 8px;">
                                        <span style="font-size: 14px; display: block; color:#383838; margin-bottom: 2px; font-weight: 600; text-align: right;"><img style="margin-top:20px;" src="assets/images/rupee.png" height="15" alt="">{{ number_format(($item->offer_price > 0 ? $item->offer_price : $item->price) * $item->qty, 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="5" style="border-top:1px solid #ccc;">
                                    <table style=" width:75%; float: right;">
                                        <tr>
                                            <td style="color: #000; padding: 8px; margin-bottom: 5px;">Subtotal</td>
                                            <td style="font-weight: 600; text-align: right;"><img style="margin-top:20px;" src="assets/images/rupee.png" height="15" alt="">{{ number_format($order->amount, 2) }}</td>
                                        </tr>
                                        @if($order->discount_amount > 0)
                                        <tr>
                                            <td style="padding:8px;">Discount</td>
                                            <td style="font-weight:600; text-align:right;">-<img style="margin-top:20px;" src="assets/images/rupee.png" height="15" alt="">{{ number_format($order->discount_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td style="color: #000; padding: 8px;">Shipping</td>
                                            <td style="font-weight: 600; text-align: right;">FREE</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:600; font-size:15px; padding:8px;">
                                                Total 
                                                <span style="display:block; font-size:13px; color:#454545;">
                                                    Including <img style="margin-top:20px;" src="assets/images/rupee.png" height="15" alt="">{{ number_format($order->tax_amount, 2) }} in taxes
                                                </span>
                                            </td>
                                            <td style="font-weight:600; font-size:15px; text-align:right;">
                                                <img style="margin-top:20px;" src="assets/images/rupee.png" height="15" alt="">{{ number_format($order->final_amount, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5" style="border-top:1px solid #ccc; padding-top: 8px;">
                                   <table style="width:100%;">
                                        <tr>
                                            <td style="width:50%; vertical-align: top;">
                                                <h3 style="color:#D0217C;">Billing Address</h3>
                                                <p style="font-weight: 300; line-height: 20px; margin-top: 0; margin-bottom: 3px;">{{ ucwords($order->fname . ' ' . $order->lname) }}</p>
                                                <p style="font-weight: 300; line-height: 20px; margin-top: 0; margin-bottom: 3px;">
                                                    
                                                   {{ $order->billing_address }}
                                                   {{ $order->billing_state}}
                                                   {{ $order->billing_country}}
                                                   {{$order->billing_pin}}
                                                </p>
                                                <p style="font-weight: 300; line-height: 20px; margin-top:0; margin-bottom:3px;">{{ $order->mobile }}</p>
                                                <p style="font-weight: 300; line-height: 20px; margin-top: 0; margin-bottom: 3px;">{{ $order->email }}</p>
                                            </td>
                                            <td style="width:50%; padding-top:50px; text-align:right;">
                                                <h3 style="color:#D0217C;">Shipping Address</h3>
                                                <p style="font-weight: 300; line-height: 20px; margin-top:0; margin-bottom:3px;">
                                                    {{ $order->shipping_address ?? 'Same as billing address' }}
                                                </p>
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
</body>
</html>