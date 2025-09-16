<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Hi {{ $order->fname }} ,</h2>
    <p>Your order <strong>#{{ $order->order_no }}</strong> has been placed successfully.</p>

    <h3>Order Details:</h3>
    <ul>
        @foreach ($order->orderProducts as $item)
            <li>{{ ucwords($item->product_name) ?? 'No Name' }} - Qty: {{ $item->qty }}</li>
        @endforeach
    </ul>

    <p>Total: ₹{{ number_format($order->total, 2) }}</p>

    <p>We will notify you when your order ships.</p>
</body>
</html>
