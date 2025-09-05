@extends('front.layout.app')
@section('page-title', 'Shop')
@section('content')

<section class="inner-banner">
    <div class="container">
        <div class="inner-heading-group">
            <h2>Products</h2>
            <ul class="breadcrumb">
                <li><a href="{{route('front.home')}}">Home</a></li>
                <li>Products</li>
            </ul>
        </div>
    </div>
</section>

<section class="inner-body">
    <div class="container">
        <div class="shop-wrapper">
            <div class="filter-area">
                <div class="filter-actions">
                    <button type="button" id="clear-filters" class="btn btn-primary">Clear Filters</button>
                </div>
                <div class="stack">
                    <h4>Category</h4>
                    <ul class="filter-list">
                        @foreach($categories as $category)
                            <li>
                                <label>
                                    <div class="style-stack">
                                        <input type="checkbox" id="cat_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" {{$selected_category==$category->id?"checked":""}}>
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span>{{ ucwords($category->name) }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="stack">
                    <h4>Weight</h4>
                    <ul class="filter-list">
                        @foreach($weights as $weight)
                            <li>
                                <label>
                                    <div class="style-stack">
                                        <input type="checkbox" name="weights[]" value="{{ $weight }}">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span>{{ $weight }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="product-listing-area">
                <div class="filter-toggle">
                    Filter <i class="fa-solid fa-arrow-up-short-wide"></i>
                </div>

                <div id="product-list">
                    @include('front.partials.filtered_products', ['data' => $data])
                </div>
            </div>
        </div>
    </div>
</section>
<div id="loader" style="display: none;">
    <div class="spinner"></div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function () {
        const categoryIds = @json($categoryIds); // Medicine & Water IDs from controller

        function fetchProducts(page = 1) {
            let selectedCategories = $("input[name='categories[]']:checked").map(function () {
                return this.value;
            }).get();

            let selectedWeights = $("input[name='weights[]']:checked").map(function () {
                return this.value;
            }).get();
            $('#loader').show(); // Show loader
            $.ajax({
                url: "{{ route('front.shop.filter') }}?page=" + page,
                type: "GET",
                data: {
                    categories: selectedCategories,
                    weights: selectedWeights
                },
                success: function (response) {
                    $('#product-list').html(response.html);
                },
                complete: function () {
                    $('#loader').hide(); // Hide loader
                }
            });

            // Show/hide weight filter
            let showWeight = selectedCategories.some(cat => categoryIds.includes(parseInt(cat)));
            if (showWeight) {
                // $(".stack:has(h4:contains('Weight'))").show();
            } else {
                // $(".stack:has(h4:contains('Weight'))").hide();
                $("input[name='weights[]']").prop('checked', false);
            }
        }

        $(document).on('change', "input[name='categories[]'], input[name='weights[]']", function () {
            fetchProducts();
        });

        // AJAX Pagination
        $(document).on('click', '#product-list .pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchProducts(page);
        });

        //  Clear filters
        $('#clear-filters').on('click', function () {
            $("input[name='categories[]'], input[name='weights[]']").prop('checked', false);
            fetchProducts(); // fetch all products
        });

        // Initial state
        // $(".stack:has(h4:contains('Weight'))").hide();
    });
</script>
@endsection
   