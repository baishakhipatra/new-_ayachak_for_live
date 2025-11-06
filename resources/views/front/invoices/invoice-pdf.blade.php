<!DOCTYPE html>
<html lang="en">
<head>
  <title>Ayachak Ashram - Invoice</title>
  <meta charset="utf-8">
</head>
<body>
  <style>
    .invoice-container-inner {
        /* padding: 20px; */
        border: 1px solid #ccc;
    }
    .invoice-container {
      width: 100%;
      margin: 20px auto;
      font-family: Arial, sans-serif;
    }
    .invoice-header {
        display: flex;
        column-gap: 25px;
        justify-content: space-between;
    }
    .address-part {
        max-width: 50%;
        flex:0 0 50%;
    }
    .invoice-logo-part {
        max-width: 50%;
        flex:0 0 50%;
    }
    .address-part h3 {
        font-size: 15px;
        margin-top: 0;
    }
    .invoice-body {
        padding-left: 8px;
        padding-right: 8px;
    }
    .invoice-body h3  {
        font-size: 15px;
        margin-top: 0;
        margin-bottom: 0;
    }
    table{
        width:100%;
        text-align: left;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 9px;
    }
    p{
        margin-top: 0;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .invoice-container, .invoice-container * {
            visibility: visible;
        }
    }
  </style>
  
    <div class="invoice-container">
        <h4 style="text-align: center; font-size: 25px; margin-bottom: 6px;">Tax Invoice</h4>
        <span style="font-size: 15px; display: block; text-align: right; margin-bottom: 16px;">Original / Duplocate Copy</span>
        <div class="invoice-container-inner">
            <table>
                <thead style="vertical-align:top;">
                    <tr>
                        <th style="width:306px;">
                            <h3 style="margin-bottom: 2px; line-height: 1.2; margin-top: 0;">AYACHAK BIPANI</h3>
                            <p style="margin-top: 0; font-weight: 400;">( A Unit of Ayachak Ashrama )</p>
                            <h4 style="font-weight: bold; margin-bottom: 6px;">GURUDHAM</h4>
                            <p style="margin-top: 0; margin-bottom: 3px; font-weight: 400;">P-238, Swami Swarupananda Sarani</p>
                            <p style="margin-top: 0; margin-bottom: 3px; font-weight: 400;">Kankurgachi, Kolkata-700054</p>
                            <p style="margin-top: 0; margin-bottom: 3px; font-weight: 400;">GST-19AAAAA1519K1ZW, State: West Bengal</p>
                        </th>
                        <th style="width:200px;">
                            <h3 style="margin:0; line-height: 1.2;">Invoice No.</h3>
                        </th>
                        <th style="width:150px;">
                            <h3 style="margin:0; line-height: 1.2;">Dated.</h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding: 0; vertical-align:top;">

                            <table style="padding-top:17px; margin-top:17px; min-height: 140px; vertical-align:top; border-top:1px solid #ccc;">
                                <tr>
                                    <td style="width:306px; vertical-align:top;">
                                        <strong style="font-size: 14px; margin-bottom: 4px; display: block;"> {{ ucwords($order->fname) }} {{ ucwords($order->lname) }}</strong>
                                        <p style="font-size: 13px;">{{ ucwords($order->fname) }} {{ $order->lname }}<br>
                                            {{ ucwords($order->shipping_address) ?? ucwords($order->billing_address) }}, 
                                            {{ ucwords($order->shipping_city) ?? ucwords($order->billing_city) }}<br>
                                            {{ ucwords($order->shipping_state) ?? ucwords($order->billing_state) }}, 
                                            {{ ucwords($order->shipping_country) ?? ucwords($order->billing_country) }}<br>
                                            Landmark: {{ ucwords($order->billing_landmark) }}<br>
                                            Alt. Phone: {{ $order->mobile }}</p>
                                    </td>
                                    <td style="vertical-align:top; width:200px; font-size: 13px;">{{ $order->order_no }}</td>
                                    <td style="vertical-align:top; width:150px; font-size: 13px;">{{ $order->created_at->format('d-M-Y') }}</td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-body">
                <table style="border:1px solid #ccc;">
                    <thead>
                        <tr>
                            <th style="border:1px solid #ccc;"><h3 style="margin:0; line-height: 1.2;">Description of goods</h3></th>
                            <th style="border:1px solid #ccc;"><h3 style="margin:0; line-height: 1.2;">HSN/SAC</h3></th>
                            <th style="border:1px solid #ccc;"><h3 style="margin:0; line-height: 1.2;">Quentity</h3></th>
                            <th style="border:1px solid #ccc;"><h3 style="margin:0; line-height: 1.2;">Rate</h3></th>
                            <th style="border:1px solid #ccc;"><h3 style="margin:0; line-height: 1.2;">Amount</h3></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderProducts as $product)
                        <tr style="border-bottom:1px solid #ccc;">
                            <td style="vertical-align: top; width:200px; font-size: 13px; border:1px solid #ccc;">
                                {{ $product->product_name }}
                            </td>
                            <td style="vertical-align: top; width:100px; font-size: 13px; border:1px solid #ccc;">
                                {{$product->productDetails->style_no ?? '-'}}
                            </td>
                            <td style="vertical-align: top; width:100px; font-size: 13px; border:1px solid #ccc;">
                                {{ $product->qty }}
                            </td>
                            <td style="vertical-align: top; width:100px; font-size: 13px; border:1px solid #ccc;">
                               ₹{{ $product->amount }}
                            </td>
                            <td style="vertical-align: top; width:100px; font-size: 13px; border:1px solid #ccc;">₹{{ number_format($product->total,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <table style="margin-top: 34px;">
                    <tr>
                        <td style="width: 50%; vertical-align: top;">
                            <strong style="text-decoration: underline; display: inline-block; margin-bottom: 6px;">Declarnation</strong>
                            <p>We declare that this invoice shows the actual price of the goods described and that
                                all particulars are true and correct. All subject to Kolkata Jurisdiction <br>
                                Hours: 11 a.m to 6 p.m. (Monday Closed) 
                            </p>
                        </td>
                        <td style="border:1px solid #ccc; vertical-align: top; border-bottom: 0; border-right: 0;">
                            <div style="height: 100%; display: flex; justify-content: space-between; flex-direction: column; height: 150px;">
                                <p style="text-align: right; font-size: 13px;">E. &. O. E for AYACHAK BIPANI</p>

                                <p style="text-align: right; font-size: 13px; margin-bottom: 4px;">Authorised Signature</p>
                            </div>
                        </td>
                    </tr>
                </table>
        </div>
        <table>
            <tr>
                <td style="font-size: 13px;">
                    GST-19AAAAA1519K1ZW
                </td>
                <td style="font-size: 13px;">JOY GURU. This is a Computer generated invoice <br>
                Cheque should be given infavour if Ayachak Bipani</td>
            </tr>
        </table>
    </div>


</body>
</html>