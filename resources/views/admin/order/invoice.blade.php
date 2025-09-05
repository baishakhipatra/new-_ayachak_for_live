@extends('admin.layouts.app')

@section('page', 'Order invoice')

@section('content')
    <style>
        .border td {
            border: 1px solid #ddd;
        }

        table,
        table p {
            font-size: 12px;
        }

        table h3 {
            font-size: 16px;
        }
    </style>
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a href="javascript: void(0)" type="button" class="btn btn-primary btn-sm" onclick="printInvoice()">Print</a>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($data->orderProducts) > 0)
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="printDiv">
                            <table border="1" class="table-bordered" style="width: 100%; border-collapse: collapse;"
                                cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <table border="1" style="width: 100%; border-collapse: collapse;"
                                            class="table-bordered" cellpadding="10" cellspacing="0">
                                            <tr>
                                                <td style="width: 35%;" rowspan="2">
                                                    <p style="margin: 0">Sender</p>
                                                    <p style="margin: 0;"><strong>{{ $settings[18]->content }}</strong><br />{!! $settings[5]->content !!}</p>
                                                    <p style="margin: 0;">Ph No: <strong>{{ $settings[6]->content }}</strong></p>
                                                    <p style="margin: 0;">GSTIN: <strong>{{ $settings[19]->content }}</strong></p>
                                                </td>
                                                <td style="width: 35%;">
                                                    <p style="margin: 0;">Invoice ID:<br /><strong>{{ $data->order_no }}</strong></p>
                                                </td>
                                                <td style="width: 30%;">
                                                    <p style="margin: 0;">Invoice Date:<br /><strong>{{date('j-M-Y', strtotime($data->created_at))}}</strong>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <p style="margin: 0;">Order No: <strong>{{ $data->order_no }}</strong></p>
                                                    <p style="margin: 0;">Order Date: {{date('j-M-Y', strtotime($data->created_at))}}</p>
                                                </td>
                                                <td>
                                                    <p style="margin: 0;">Portal: <strong>{{ $settings[18]->content }}</strong></p>
                                                    <p style="margin: 0;">Payment Mode</p>
                                                    <p style="margin: 0;"><strong>
                                                        @php
                                                            if ($data->payment_method == "cash_on_delivery") {
                                                                echo 'Cash on Delivery';
                                                            } else {
                                                                echo 'Online Payment';
                                                            }
                                                        @endphp
                                                    </strong></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="margin: 0;">Bill To: <br />
                                                        <strong>{{$data->fname.' '.$data->lname}}</strong>
                                                        <br />{{$data->billing_address}}, {{$data->billing_landmark ? $data->billing_landmark.', ' : ''}} {{$data->billing_pin.', '.$data->billing_city.', '.$data->billing_state.', '.$data->billing_country}}</p>
                                                    <p style="margin: 0;">T : {{$data->mobile}}</p>
                                                </td>
                                                <td>
                                                    <p style="margin: 0;">Ship To: <br />
                                                        <strong>{{$data->fname.' '.$data->lname}}</strong>
                                                        <br />{{$data->shipping_address ?? $data->billing_address}},

                                                        {{$data->shipping_landmark ? $data->shipping_landmark.',' : ($data->billing_landmark ? $data->billing_landmark.', ' : '')}}

                                                        {{$data->shipping_pin ? $data->shipping_pin.',' : ($data->billing_pin ? $data->billing_pin.', ' : '')}}

                                                        {{$data->shipping_city ? $data->shipping_city.',' : ($data->billing_city ? $data->billing_city.', ' : '')}}

                                                        {{$data->shipping_state ? $data->shipping_state.',' : ($data->billing_state ? $data->billing_state.', ' : '')}}

                                                        {{$data->shipping_country ? $data->shipping_country : ($data->billing_country ? $data->billing_country : '')}}

                                                        {{-- {{$data->shipping_pin ?? $data->billing_pin.', '.$data->shipping_city ?? $data->billing_city.', '.$data->shipping_state ?? $data->billing_state.', '.$data->shipping_country ?? $data->billing_country}} --}}
                                                    </p>
                                                    <p style="margin: 0;">T : {{$data->mobile}}</p>
                                                </td>
                                                <td>
                                                    {{-- <p style="margin: 0;">Dispatch Through<br /><strong>EK</strong><br />AWB
                                                        No<br /> MYNP0032032743</p> --}}
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
                                                <th align="center">Sl No.</th>
                                                <th align="center">Descriptions of Goods</th>
                                                <th align="center">Part No</th>
                                                <th align="center">Qty</th>
                                                <th align="center">MRP</th>
                                                <th align="center">Discount</th>
                                                <th align="center">Taxable Value<br />(INR)</th>
                                                <th align="center">CGST (INR)</th>
                                                <th align="center">SGST (INR)</th>
                                                <th align="center">IGST (INR)</th>
                                                <th align="center">Amount (INR)</th>
                                            </tr>
                                            @foreach($data->orderProducts as $productKey => $productVal)
                                                @php
                                                    // name
                                                    $product_name = $productVal->product_name;

                                                    // sku code
                                                    $variation_id = $productVal->product_variation_id;
                                                    $skuCode = \DB::table('product_color_sizes')->select('code')->where('id', $variation_id)->first();
                                                    $product_sku = $skuCode ? $skuCode->code : '';

                                                    // product quantity
                                                    $order_product_qty = $productVal->qty;

                                                    // product selling price
                                                    $discountAmount=0;
                                                     if ($data->coupon_code_id != 0){
                                                                                                  if ($data->coupon_code_discount_type == 'Percentage'){
                                                                       
            
                                                                       
                                                                            $discountAmount = ceil(($productVal->offer_price * $data->discount_amount) / 100);
                                                                       
                                                                   

                                                       
                                                    }else{
                                                        $discountAmount = $data->discount_amount;
                                                        }
                                                     
                                                     $selling_offer_price = ($productVal->offer_price-$discountAmount);
                                                     }else{
                                                    $selling_offer_price = $productVal->offer_price;
                                                    }
                                                    $selling_price = $productVal->offer_price;
                                                    $selling_price_in_decimal = sprintf('%.2f', $selling_price);

                                                    // total product selling value
                                                    $amount = $selling_price * $order_product_qty;
                                                    $amount_in_decimal = sprintf('%.2f', $amount);

                                                    // GST CALCULATION
                                                    $state = $data->billing_state;
                                                    $cgst = CGSTCalculation($state, $selling_offer_price, $order_product_qty);
											        //dd($cgst);
                                                    $sgst = SGSTCalculation($state, $selling_offer_price, $order_product_qty);
                                                    $igst = IGSTCalculation($state, $selling_offer_price, $order_product_qty);
											        $tax=taxCalculation($selling_offer_price, $order_product_qty);
											       //dd($tax);
                                                @endphp

                                                <tr>
                                                    <td align="center">1</td>
                                                    <td><strong>{{ $product_name }}</strong></td>
                                                    <td>{{ $product_sku }}</td>
                                                    <td align="center">{{ $order_product_qty }}</td>
                                                    <td align="right">{{ $selling_price_in_decimal }}</td>

                                                    {{--<td align="right">
														 {{ sprintf('%.2f', $tax[2]) }}
                                                       
                                                    </td>--}}
                                                     @php
                                                        $discountAmount = 0;
                                                    @endphp

                                                    @if ($data->coupon_code_id != 0)
                                                        @if ($data->coupon_code_discount_type == 'Percentage')
                                                            

                                                            @php
                                                                $discountAmount = ceil((($selling_price_in_decimal *$order_product_qty) *  $data->discount_amount) / 100);
                                                            @endphp
                                                        @else
                                                           
                                                            @php
                                                                $discountAmount = $data->discount_amount;
                                                            @endphp
                                                        @endif

                                                       
                                                    @else
                                                        {{sprintf("%.2f", $data->discount_amount)}}
                                                    @endif
                                                    <td align="right">
                                                        {{$discountAmount}}
                                                </td>
                                                    <td align="right">
														{{ sprintf('%.2f', $tax[3]) }}
                                                        
                                                    </td>

                                                    <td align="right">
                                                        @if ($cgst)
														  @if(($selling_offer_price)< 1000)
                                                            {!! $cgst[0] . '<br>(' . sprintf('%.1f',2.5) . '%)' !!}
														  @else
															{!! $cgst[0] . '<br>(' . sprintf('%.1f',6) . '%)' !!}								
														  @endif
                                                        @endif
                                                    </td>

                                                    <td align="right">
                                                        @if ($sgst)
														@if(($selling_offer_price)< 1000)
                                                            {!! $sgst[0] . '<br>(' . sprintf('%.1f', 2.5) . '%)' !!}
														@else
															{!! $sgst[0] . '<br>(' . sprintf('%.1f',6) . '%)' !!}								
														 @endif
																							  
                                                        @endif
                                                    </td>
													
                                                    <td align="right">
                                                        @if ($igst)
														  
														   {!! $igst[0] . '<br>(' . sprintf('%.1f', $igst[1]) . '%)' !!}
														 
                                                        @endif
                                                    </td>
                                                    
                                                    <td align="right">{{ $amount_in_decimal - $discountAmount }}</td>
                                                </tr>
                                            @endforeach

                                            

                                            <tr style="border-width: 1px;padding: 5px 10px;">
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"><strong>Amount</strong></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;" align="right">{{sprintf("%.2f", $data->final_amount )}}</td>
                                            </tr>
                                           
                                            @if ($data->coupon_code_id != 0)
                                            <tr style="border-width: 1px;padding: 5px 10px;">
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"><strong>Subtotal</strong></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;" align="right">
                                                    {{ sprintf('%.2f', ($data->final_amount )) }}
                                                </td>
                                            </tr>
                                            @endif
                                            <tr style="border-width: 1px;padding: 5px 10px;">
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"><strong>Shipping Charges</strong></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                @if ($data->shipping_charges > 0)
                                                    @php
                                                        // shipping tax calculation
                                                        $shipping_charge = $data->shipping_charges;
														if($data->address_type=='ho'){
															$shipping_tax = 0.00;
														}elseif($data->address_type=='dankuni'){
															$shipping_tax = 0.00;
														}else{
                                                        $shipping_tax = sprintf('%.2f', ($shipping_charge * 5) / 105);
														}
														if($data->address_type=='ho'){
															$shipping_taxable_value = 0.00;
														}elseif($data->address_type=='dankuni'){
															$shipping_taxable_value = 0.00;

														}else{
                                                        $shipping_taxable_value = $shipping_charge - $shipping_tax;
														}
                                                        // $igst = IGSTCalculation($state, $selling_price, $order_product_qty);
                                                    @endphp

                                                    @if (!stateCheck($data->billing_state))
                                                        <td style="border-width: 1px;padding: 5px 10px;" align="right">{{ $shipping_taxable_value }}</td>
                                                        <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                        <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                        <td style="border-width: 1px;padding: 5px 10px;" align="right">{{ $shipping_tax }}</td>
                                                    @else
                                                        <td style="border-width: 1px;padding: 5px 10px;" align="right">{{ $shipping_taxable_value }}</td>
                                                        {{-- <td style="border-width: 1px;padding: 5px 10px;"></td> --}}
                                                        <td style="border-width: 1px;padding: 5px 10px;" align="right" colspan="2">{{ $shipping_tax }}</td>
                                                        <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                    @endif
                                                @else
                                                    <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                    <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                    <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                    <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                @endif
												@if($data->address_type=='ho')
												<td style="border-width: 1px;padding: 5px 10px;" align="right">
													{{-- {{ ($data->shipping_charges > 0) ? '+' : '' }} --}}
													0
												</td>
												@elseif($data->address_type=='dankuni')
												<td style="border-width: 1px;padding: 5px 10px;" align="right">
													{{-- {{ ($data->shipping_charges > 0) ? '+' : '' }} --}}
													0
												</td>
												@else
                                                <td style="border-width: 1px;padding: 5px 10px;" align="right">
                                                    {{-- {{ ($data->shipping_charges > 0) ? '+' : '' }} --}}
                                                    {{sprintf("%.2f", $data->shipping_charges)}}
                                                </td>
												@endif
                                                {{-- <td style="border-width: 1px;padding: 5px 10px;" align="right">{{sprintf("%.2f", $data->shipping_charges)}}</td> --}}
                                            </tr>
                                            <tr style="border-width: 1px;padding: 5px 10px;">
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"><strong>Final Amount</strong></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
                                                <td style="border-width: 1px;padding: 5px 10px;"></td>
												@if($data->address_type=='ho')
													 	<td style="border-width: 1px;padding: 5px 10px;" align="right">{{sprintf("%.2f", ($data->final_amount)-($data->shipping_charges))}}</td>
													@elseif($data->address_type=='dankuni')
													 	<td style="border-width: 1px;padding: 5px 10px;" align="right">{{sprintf("%.2f", ($data->final_amount)-($data->shipping_charges))}}</td>
													@else
                                                		<td style="border-width: 1px;padding: 5px 10px;" align="right">{{sprintf("%.2f", $data->final_amount)}}</td>
													@endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" style="width: 100%; border-collapse: collapse;"
                                            class="" cellpadding="10" cellspacing="0">
                                            <tr>
                                                <td valign="top">
													@php
									   				$amount=($data->final_amount)-($data->shipping_charges);
													@endphp
													@if($data->address_type=='ho')
													<p style="margin: 0;">Amount Chargeable (in words)<br /><strong>INR {{ amountInWords($amount) }} Only Tax is payable on reverse charge basis: No</strong></p>
													@elseif($data->address_type=='dankuni')
													<p style="margin: 0;">Amount Chargeable (in words)<br /><strong>INR {{ amountInWords($amount) }} Only Tax is payable on reverse charge basis: No</strong></p>
													@else
                                                    <p style="margin: 0;">Amount Chargeable (in words)<br /><strong>INR {{ amountInWords($data->final_amount) }} Only Tax is payable on reverse charge basis: No</strong></p>
													@endif
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
                                                        West Bengal (19) jurisdiction only.</p>
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
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="display-6 text-danger">Invalid Order</h5>
                        <p class="text-muted">Customer refreshed Order success page</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('js/printThis.js') }}"></script>

    <script>
        function printInvoice() {
            $('.printDiv').printThis({
                pageTitle: "{{$data->order_no}}"
            });
        }
    </script>
@endsection
