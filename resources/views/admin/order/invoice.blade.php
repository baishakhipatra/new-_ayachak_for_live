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
                            {{ config('app.name') }}<br>
                            {{-- You can hardcode company address here --}}
                            Address line 1, City, State, Country <br>
                            GSTIN: 1234567890
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
                            {{ $data->fname }} {{ $data->lname }}<br>
                            {{ $data->billing_address }}, {{ $data->billing_city }}, {{ $data->billing_state }}, {{ $data->billing_country }}<br>
                            Landmark: {{ $data->billing_landmark }}<br>
                            Phone: {{ $data->mobile }}
                        </div>
                        <div>
                            <strong>Ship To:</strong><br>
                            {{ $data->fname }} {{ $data->lname }}<br>
                            {{ $data->shipping_address ?? $data->billing_address }},
                            {{ $data->shipping_city ?? $data->billing_city }},
                            {{ $data->shipping_state ?? $data->billing_state }},
                            {{ $data->shipping_country ?? $data->billing_country }}<br>
                            Landmark: {{ $data->billing_landmark }}<br>
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
                                <th class="text-end">GST (₹)</th>
                                <th class="text-end">Discount (₹)</th>
                                <th class="text-end">Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->orderProducts as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->qty }}</td>
                                    <td>{{ number_format($product->total, 2) }}</td>
                                    <td class="text-end">{{ number_format(($product->total) - ($product->gst_amount),2) }}</td>
                                    <td class="text-end">{{ number_format($product->gst_amount,2) }}</td>
                                    <td class="text-end"></td>
                                    <td class="text-end">{{ number_format($product->total,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end">Grand Total (₹)</th>
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

