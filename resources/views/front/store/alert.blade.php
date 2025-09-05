@extends('layouts.app')

@section('page', 'Home')

@section('content')
    <section class="store_details">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h5>You already have product in your cart.</h5>
                    <p class="small">If you proceed you might lose your cart data. Still want to continue, cowboy?
                    </p>
                    <a href="{{ route('front.store.index') }}" class="btn">Back to stores</a>
                    @if (request()->input('type'))
                        <a href="{{ route('front.store.cart.visit-store', [$id, 'visit' => 'force', 'type' => request()->input('type')]) }}"
                            class="btn btn-danger">Visit store</a>
                    @else
                        <a href="{{ route('front.store.detail', [$id, 'visit' => 'force']) }}" class="btn btn-danger">Visit
                            store</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script></script>
@endsection
