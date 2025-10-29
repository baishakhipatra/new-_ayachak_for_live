    @extends('admin.layouts.app')

@section('page', 'Order Invoice')

@section('content')
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .invoice-table, .invoice-table th, .invoice-table td { border: 1px solid #000; border-collapse: collapse; }
    .invoice-table th, .invoice-table td { padding: 5px; text-align: left; }
    .no-border { border: none !important; }
    .right { text-align: right; }
</style>

<section>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body printDiv">

                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Sold By / Billed From:</strong><br>
                            {{ "Ayachak Ashrama" }}<br>
                            {{-- You can hardcode company address here --}}
                            <h5>Head Office</h5>
                            GURU-DHAM
                            P-238, Swami Swarupananda Sarani
                            P.O. - Kankurgachi,
                            Kolkata-700054
                            Phone-2320-8455/5559
                        </div>
                        <div class="text-end">
                            <strong>Invoice Number:</strong> {{ $data->order_no }}<br>
                            <strong>Invoice Date:</strong> {{ $data->created_at->format('d-M-Y') }}
                        </div>
                    </div>

                    <hr>

                    {{-- Billing / Shipping --}}
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Bill To:</strong><br>
                            {{ ucwords($data->fname) }} {{ ucwords($data->lname) }}<br>
                            {{ ucwords($data->billing_address) }}, {{ ucwords($data->billing_city) }}, {{ ucwords($data->billing_state) }}, {{ ucwords($data->billing_country) }}<br>
                            Landmark: {{ ucwords($data->billing_landmark) }}<br>
                            Phone: {{ $data->mobile }}
                        </div>
                        <div>
                            <strong>Ship To:</strong><br>
                            {{ ucwords($data->fname) }} {{ $data->lname }}<br>
                            {{ ucwords($data->shipping_address) ?? ucwords($data->billing_address) }},
                            {{ ucwords($data->shipping_city) ?? ucwords($data->billing_city) }},
                            {{ ucwords($data->shipping_state) ?? ucwords($data->billing_state) }},
                            {{ ucwords($data->shipping_country) ?? ucwords($data->billing_country) }}<br>
                            Landmark: {{ ucwords($data->billing_landmark) }}<br>
                            Alernative Phone: {{ $data->alt_mobile }}
                        </div>
                    </div>

                    <br>

                    {{-- Items --}}
                    <table class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price (₹)</th>
                                <th class="text-end">Taxable Value (₹)</th>
                                <th class="text-end">CGST (₹)</th>
                                <th class="text-end">SGST (₹)</th>
                                <th class="text-end">IGST (₹)</th>
                                <th class="text-end">Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->orderProducts as $product)
                                @php
                                    $gstPercent = $product->gst;
                                    $gstAmount = $product->gst_amount;

                                    $cgst = 0;
                                    $sgst = 0;
                                    $igst = 0;

                                    $cgstPercent = 0;
                                    $sgstPercent = 0;
                                    $igstPercent = 0;

                                    if(strtolower($data->shipping_state) == 'west bengal') {
                                        $cgst = $gstAmount / 2;
                                        $sgst = $gstAmount / 2;

                                        $cgstPercent = $gstPercent /2;
                                        $sgstPercent = $gstPercent/2;
                                    } else {
                                        $igst = $gstAmount;
                                        $igstPercent = $gstPercent;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->qty }}</td>
                                    <td>{{ number_format($product->total, 2) }}</td>
                                    <td class="text-end">{{ number_format(($product->total - $gstAmount),2) }}</td>
                                    <td class="text-end">{{ number_format($cgst,2) }} 
                                    @if($cgstPercent > 0)
                                        <small class="text-muted">({{ rtrim(rtrim(number_format($cgstPercent, 2), '0'), '.') }}%)</small>
                                    @endif</td>
                                    <td class="text-end">{{ number_format($sgst,2) }}
                                    @if($sgstPercent > 0)
                                        <small class="text-muted">({{ rtrim(rtrim(number_format($sgstPercent, 2), '0'), '.') }}%)</small>
                                    @endif
                                    </td>
                                    <td class="text-end">{{ number_format($igst,2) }}
                                    @if($igstPercent > 0)
                                        <small class="text-muted">({{ rtrim(rtrim(number_format($igstPercent, 2), '0'), '.') }}%)</small>
                                    @endif
                                    </td>
                                    <td class="text-end">{{ number_format($product->total,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" class="text-end">Grand Total (₹)</th>
                                <th class="text-end">{{ number_format($data->amount, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="8" class="text-end">Discount</th>
                                <th class="text-end">{{ number_format($data->discount_amount, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="8" class="text-end">Final Amount</th>
                                <th class="text-end">{{ number_format($data->final_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    <br>

                    {{-- Footer --}}
                    <div class="d-flex justify-content-between">
                        <div>
                            <p>Amount Chargeable (in words): INR {{ amountInWords($data->final_amount) }} Only</p>
                        </div>
                        <div class="text-end">
                            <strong>Authorized Signatory</strong><br>
                            ____________________
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="printInvoice()">Print</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="{{ asset('js/printThis.js') }}"></script>
<script>
    function printInvoice() {
        $('.printDiv').printThis({
            pageTitle: '{{ $data->order_no }}'
        });
    }
</script>
@endsection 

