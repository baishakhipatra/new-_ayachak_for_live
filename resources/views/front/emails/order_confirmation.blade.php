<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Hi {{ $order->fname }},</h2>
    <p>Your order <strong>#{{ $order->order_no }}</strong> has been placed successfully.</p>

    <h3>Order Details:</h3>
    <ul>
        @foreach ($order->orderProducts as $item)
            <li>
                {{ ucwords($item->product_name) }} 
                - Qty: {{ $item->qty }} 
                - Price: ₹{{ $item->price }}
            </li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> ₹{{ $order->final_amount }}</p>
    <p>We will notify you when your order ships.</p> 

</body>
</html>
