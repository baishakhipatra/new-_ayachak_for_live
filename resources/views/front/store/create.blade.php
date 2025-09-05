@extends('layouts.app')

@section('page', 'Store add')

@section('content')
@php
    $state_area = DB::select('SELECT state, area FROM retailer_list_of_occ GROUP BY area ORDER BY state ASC, area ASC');
@endphp
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Create new store</h3>

        <section class="store_listing">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <form class="createField" action="{{ route('front.store.store') }}" method="POST" enctype="multipart/form-data">@csrf
                            <div class="row">
                                <div class="col-12"><h4 class="mb-3">Store information</h4></div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Store Name</label>
                                        <input type="text" class="form-control" placeholder="Store Name" name="store_name" value="{{old('store_name')}}">
                                        @error('store_name') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Distributor Name</label>
                                        <select class="form-control" name="bussiness_name">
                                            <option hidden selected>Select Distributor</option>
                                            @foreach ($distributor as $index => $item)
                                                <option value="{{$item->distributor_name}}" {{ ($item->distributor_name == old('bussiness_name')) ? 'selected' : '' }}>{{$item->distributor_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('bussiness_name') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Store OCC number</label>
                                        <input type="text" class="form-control" placeholder="Store OCC number" name="store_OCC_number" value="{{old('store_OCC_number')}}">
                                        @error('store_OCC_number') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div> --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Image</label>
                                        <input type="file" class="form-control" placeholder="Address" name="image" value="{{old('image')}}">
                                        @error('image') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">GST number</label>
                                        <input type="text" class="form-control" placeholder="Store GST number" name="gst_no" value="{{old('gst_no')}}">
                                        @error('gst_no') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12"><h4 class="pt-3 mb-3">Contact information</h4></div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Contact</label>
                                        <input type="text" class="form-control" placeholder="Contact" name="contact" value="{{old('contact')}}" onkeypress="return onlyNumberKey(event)">
                                        @error('contact') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Email</label>
                                        <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email')}}">
                                        @error('email') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Whatsapp</label>
                                        <input type="text" class="form-control" placeholder="Whatsapp" name="whatsapp" value="{{old('whatsapp')}}" onkeypress="return onlyNumberKey(event)">
                                        @error('whatsapp') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12"><h4 class="pt-3 mb-3">Address information</h4></div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Address</label>
                                        <input type="text" class="form-control" placeholder="Address" name="address" value="{{old('address')}}">
                                        @error('address') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                @php
                                    $statesOnly = [];
                                    foreach ($state_area as $stateKey => $stateValue) {
                                        if (!in_array($stateValue->state, $statesOnly)) {
                                            array_push($statesOnly, $stateValue->state);
                                        }
                                    }
                                    $selectedState = Auth::guard('web')->user()->state ? Auth::guard('web')->user()->state : $statesOnly[0];
                                @endphp
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">State</label>
                                        {{-- <input type="text" class="form-control" placeholder="State" name="state" value="{{old('state')}}"> --}}
                                        <select name="state" class="form-control" onchange="cityGenerate($(this).val())">
                                            @foreach ($statesOnly as $statesOnlyvalue)
                                                <option value="{{ $statesOnlyvalue }}"
                                                    {{ $selectedState == $statesOnlyvalue ? 'selected' : '' }}>
                                                    {{ $statesOnlyvalue }}</option>
                                            @endforeach
                                        </select>
                                        @error('state') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Area</label>
                                        {{-- <input type="text" class="form-control" placeholder="Area" name="area" value="{{old('area')}}"> --}}
                                        <select name="area" class="form-control"></select>
                                        @error('area') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">City</label>
                                        <input type="text" class="form-control" placeholder="City" name="city" value="{{old('city')}}">
                                        @error('city') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div> --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="floating-label">Pin</label>
                                        <input type="text" class="form-control" placeholder="Pin" name="pin" value="{{old('pin')}}" onkeypress="return onlyNumberKey(event)">
                                        @error('pin') <p class="small text-danger">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="profile-card-footer">
                                <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                                <button type="submit" class="btn sweetRedBtn">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <div>
<div>
@endsection

@section('script')
    <script>
        function cityGenerate(state) {
            const statesArr = '<?php echo json_encode($state_area); ?>';
            const parsedStatesArr = JSON.parse(statesArr);

            var city = '';
            $.each(parsedStatesArr, (key, value) => {
                if (value.state == state) {
                    city += `<option value="${value.area}" ${value.area == '{{ Auth::guard('web')->user()->city }}' ? 'selected' : '' }>${value.area}</option>`;
                }
            });
            $('select[name="area"]').html(city);
        }
        cityGenerate('{{ $selectedState }}');
    </script>
@endsection