@extends('admin.layouts.app')

@section('page', 'Order detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-3">
            <div class="card shadow-sm">
                <div class="card-header">Main image</div>
                <div class="card-body">
                    <div class="w-100 product__thumb">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <p class="small">Order Time : {{date('j M Y g:i A', strtotime($data->created_at))}}</p>
                        <h2>{{$data->fname.' '.$data->lname}}</h2>
                        <p class="small text-dark mb-0"> <span class="text-muted">Email : </span> {{$data->email}}</p>
                        <p class="small text-dark mb-0"> <span class="text-muted">Mobile : </span> {{$data->mobile}}</p>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="small text-dark mb-2"> <span class="text-muted">Billing address : </span></p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Street address : </span> {{$data->billing_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->billing_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->billing_pin.', '.$data->billing_city.', '.$data->billing_state.', '.$data->billing_country}}</p>
                        </div>

                        <div class="col-md-6 border-start">
                            <p class="small text-dark mb-2"> <span class="text-muted">Shipping address : </span></p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Street address : </span> {{$data->shipping_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->shipping_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->shipping_pin.', '.$data->shipping_city.', '.$data->shipping_state.', '.$data->shipping_country}}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-4 text-end">
                            <p class="small text-muted mb-2">Pricing</p>
                            <table class="w-100">
                                <tr>
                                    <td><p class="small text-muted mb-0">Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">Rs {{$data->amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Tax Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">+ Rs {{$data->tax_amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Discount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">- Rs {{$data->discount_amount}}</p></td>
                                </tr>
                                <tr class="border-top">
                                    <td><p class="small text-muted mb-0">Final Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">Rs {{$data->final_amount}}</p></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
    </script>
@endsection