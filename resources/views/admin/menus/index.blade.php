@extends('admin.layouts.app')

@section('page', 'Menus')

@section('content')

<div class="container">

    <form method="POST" action="{{ route('admin.menus.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4"><input type="text" name="title" class="form-control" placeholder="Menu Title" required></div>
            <div class="col-md-4"><input type="text" name="url" class="form-control" placeholder="Menu URL"></div>
            <div class="col-md-4"><button class="btn btn-success">Add Menu</button></div>
        </div>
    </form>

    <div class="dd" id="nestable">
        <ol class="dd-list">
            @foreach ($menus as $menu)
                <li class="dd-item" data-id="{{ $menu->id }}">
                    <div class="dd-handle">{{ $menu->title }}</div>
                    @if ($menu->children->count())
                        <ol class="dd-list">
                            @foreach ($menu->children as $child)
                                <li class="dd-item" data-id="{{ $child->id }}">
                                    <div class="dd-handle">{{ $child->title }}</div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    <button class="btn btn-primary mt-3" id="saveMenuOrder">Save Order</button>
</div>
@endsection

@section('script')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.js"></script>

<script>
    $('#nestable').nestable();

    $('#saveMenuOrder').click(function () {
        let order = $('#nestable').nestable('serialize');

        $.ajax({
            url: '{{ route('admin.menus.updateOrder') }}',
            method: 'POST',
            data: {
                menu: order,
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                alert('Menu order saved!');
            }
        });
    });
</script>
@endsection
