<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Cancelled</title>
</head>

<body style="font-family: Arial, sans-serif; color:#333; margin:0; padding:0;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:700px; margin:0 auto; padding:20px;">

<tr>
    <td style="font-size:15px;">
        Dear {{ ucwords($order->fname) }} {{ ucwords($order->lname) }}, <br><br>

        <strong>Your order has been cancelled.</strong> <br><br>

        <strong>Order No :</strong> <span style="color:#000;">#{{ $order->order_no }}</span><br>
        <strong>Value of Order :</strong> <span style="color:#000;">₹ {{ number_format($order->final_amount,2) }}</span>
        <br><br>

        @if($order->orderCancelledReason)
        <strong>Cancellation Reason:</strong> <br>
        <p>{{ $order->orderCancelledReason }}</p>
        @endif

        <br><br>
        In case this cancellation was not done by you, please contact us immediately. <br><br>
    </td>
</tr>

<tr>
    <td style="font-size:15px;">
        Contact us at  
        <a href="mailto:{{'info@ayachakashram.com' }}" style="color:#d00000; text-decoration:none;">
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
