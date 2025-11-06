<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Shipped</title>
</head>

<body style="font-family: Arial, sans-serif; color:#333; margin:0; padding:0;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:700px; margin:0 auto; padding:20px;">

<tr>
    <td style="font-size:15px;">
        Dear {{ ucwords($order->fname) }} {{ ucwords($order->lname) }}, <br><br>

        <strong>Your order has been shipped!</strong>  
        It is now on the way to your delivery address. <br><br>

        <strong>Your Order No :</strong> <span style="color:#000;">#{{ $order->order_no }}</span><br>
        <strong>Value of Order :</strong> <span style="color:#000;">₹ {{ number_format($order->final_amount,2) }}</span>
        <br><br>

        Below is your order summary: <br><br>
    </td>
</tr>

<!-- Product Table -->
<tr>
    <td>
        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse; font-size:14px;">
            <tr>
                <th style="text-align:left; border-bottom:1px solid #ccc;">Sl. No.</th>
                <th style="text-align:left; border-bottom:1px solid #ccc;">Particulars</th>
                <th style="text-align:left; border-bottom:1px solid #ccc;">Quantity</th>
                <th style="text-align:left; border-bottom:1px solid #ccc;">Amount</th>
            </tr>

            @php $i = 1; @endphp
            @foreach($order->orderProducts as $product)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->qty }}</td>
                <td>₹ {{ number_format($product->total, 2) }}</td>
            </tr>
            @endforeach
        </table>
    </td>
</tr>

<tr>
    <td style="padding-top:20px; font-size:15px;">
        You will receive delivery updates soon. <br><br>

        For any query, contact us at  
        <a href="mailto:{{ 'info@ayachakashram.com' }}" style="color:#d00000; text-decoration:none;">
            Email us
        </a> 
        or Call at  
        <span style="color:#d00000; font-weight:bold;">
            {{ '2320-8455/5559' }}
        </span>

        <br><br>
        Regards<br>
        <strong>Team {{ 'Ayachak Ashrama' }}</strong>
    </td>
</tr>

</table>
</body>
</html>
