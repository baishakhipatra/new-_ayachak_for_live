<!DOCTYPE html>
<html lang="en">
<head>
  <title>Invoice #{{ $order->order_no }}</title>
  <meta charset="utf-8">
  <style>
      body {
          font-family: 'Segoe UI', Arial, sans-serif;
          font-size: 13px;
          color: #333;
          margin: 0;
          padding: 20px;
          background: #f8f9fa;
      }
      .invoice-box {
          background: #fff;
          padding: 25px;
          border-radius: 8px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }
      h2 {
          margin: 0;
          font-size: 20px;
          color: #444;
      }
      .header {
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
      }
      .header-left {
          max-width: 60%;
      }
      .header-right {
          text-align: right;
      }
      hr {
          border: none;
          border-top: 1px solid #ddd;
          margin: 20px 0;
      }
      .addresses {
          display: flex;
          justify-content: space-between;
          margin-top: 10px;
      }
      .address {
          width: 48%;
          line-height: 1.5;
      }
      .address strong {
          display: block;
          margin-bottom: 5px;
          text-decoration: underline;
      }
      table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
      }
      table th, table td {
          border: 1px solid #ddd;
          padding: 8px 10px;
      }
      table th {
          background: #f1f1f1;
          font-weight: 600;
          text-align: left;
      }
      table td.text-end, th.text-end {
          text-align: right;
      }
      tfoot th {
          background: #fafafa;
      }
      .footer {
          display: flex;
          justify-content: space-between;
          margin-top: 30px;
      }
      .amount-in-words {
          font-style: italic;
          font-size: 12px;
          color: #555;
      }
      .signature {
          text-align: right;
      }
  </style>
</head>
<body>
<div class="invoice-box">
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h2>Invoice</h2>
            <p><strong>Sold By / Billed From:</strong><br>
                Ayachak Ashrama<br>
                <strong>Head Office</strong><br>
                GURU-DHAM, P-238, Swami Swarupananda Sarani<br>
                P.O. - Kankurgachi, Kolkata-700054<br>
                Phone: 2320-8455 / 5559
            </p>
        </div>
        <div class="header-right">
            <p>
                <strong>Invoice Number:</strong> {{ $order->order_no }}<br>
                <strong>Invoice Date:</strong> {{ $order->created_at->format('d-M-Y') }}
            </p>
        </div>
    </div>

    <hr>

    <!-- Billing & Shipping -->
    <div class="addresses">
        <div class="address">
            <strong>Bill To</strong>
            {{ ucwords($order->fname) }} {{ ucwords($order->lname) }}<br>
            {{ ucwords($order->billing_address) }}, {{ ucwords($order->billing_city) }}<br>
            {{ ucwords($order->billing_state) }}, {{ ucwords($order->billing_country) }}<br>
            Landmark: {{ ucwords($order->billing_landmark) }}<br>
            Phone: {{ $order->mobile }}
        </div>
        <div class="address">
            <strong>Ship To</strong>
            {{ ucwords($order->fname) }} {{ $order->lname }}<br>
            {{ ucwords($order->shipping_address) ?? ucwords($order->billing_address) }}, 
            {{ ucwords($order->shipping_city) ?? ucwords($order->billing_city) }}<br>
            {{ ucwords($order->shipping_state) ?? ucwords($order->billing_state) }}, 
            {{ ucwords($order->shipping_country) ?? ucwords($order->billing_country) }}<br>
            Landmark: {{ ucwords($order->billing_landmark) }}<br>
            Alt. Phone: {{ $order->alt_mobile }}
        </div>
    </div>

    <!-- Items -->
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Qty</th>
            <th class="text-end">Price</th>
            <th class="text-end">Taxable Value</th>
            <th class="text-end">GST</th>
            <th class="text-end">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderProducts as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->qty }}</td>
                <td class="text-end">{{ number_format($product->total, 2) }}</td>
                <td class="text-end">{{ number_format(($product->total) - ($product->gst_amount),2) }}</td>
                <td class="text-end">{{ number_format($product->gst_amount,2) }}</td>
                <td class="text-end">{{ number_format($product->total,2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="6" class="text-end">Grand Total</th>
            <th class="text-end">{{ number_format($order->amount, 2) }}</th>
        </tr>
        <tr>
            <th colspan="6" class="text-end">Discount</th>
            <th class="text-end">- {{ number_format($order->discount_amount, 2) }}</th>
        </tr>
        <tr>
            <th colspan="6" class="text-end">Final Amount</th>
            <th class="text-end">{{ number_format($order->final_amount, 2) }}</th>
        </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>
            <p class="amount-in-words">Amount Chargeable (in words):<br>
                INR {{ amountInWords($order->final_amount) }} Only</p>
        </div>
        <div class="signature">
            <strong>Authorized Signatory</strong>
            ____________________
        </div>
    </div>
</div>
</body>
</html>
