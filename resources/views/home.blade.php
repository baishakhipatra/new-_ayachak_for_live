@extends('layouts.app')

@section('content')

<!-- @if (Auth::guard('web')->check())
    <script>window.location = "{{route('front.profile')}}"</script>
@else
    <script>window.location = "{{route('front.home')}}"</script>
@endif -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
