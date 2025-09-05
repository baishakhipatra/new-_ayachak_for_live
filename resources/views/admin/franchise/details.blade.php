@extends('admin.layouts.app')

@section('page', 'Franchise Partner detail')

@section('content')
<style type="text/css">
    .col-form-label {
        color: #888;
    }
</style>
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="admin__content">
                        <aside>
                            <nav>Franchise Partner Detail</nav>
                        </aside>
                        <content>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Name:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->name }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Email:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->email }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Phone:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->phone }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">City:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->city }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Nature of Current Business:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->business_nature }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Which territory or region are you interested in? *:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->region }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Is Property Available?:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->property_type }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Available Capital?:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->capital }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">How did you hear about our franchise opportunities?:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->source }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Comments:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->comment }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Remarks:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->remarks }}
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5">
                                    <label for="inputPassword6" class="col-form-label">Created at:</label>
                                </div>
                                <div class="col-auto">
                                    {{ $data->created_at }}
                                </div>
                            </div>
                        </content>
                    </div>
                    <?php /* ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ $data->name }}</h3>
                            <p class="text-muted">{{ $data->email }}</p>
                            <p class="small">{{ $data->phone }}</p>
                            <p class="small">{{ $data->city }}</p>
                            <p class="small">{{ $data->business_nature }}</p>
                            <p class="small">{{ $data->region }}</p>
                            <p class="small">{{ $data->property_type }}</p>
                            <p class="small">{{ $data->capital }}</p>
                            <p class="small">{{ $data->source }}</p>
                            <p class="small">{{ $data->comment }}</p>
                            <p class="small">{{ $data->remarks }}</p>
                            <p class="small">{{ $data->created_at }}</p>
                            <hr>
                        </div>
                    </div>
                    <?php */ ?>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
