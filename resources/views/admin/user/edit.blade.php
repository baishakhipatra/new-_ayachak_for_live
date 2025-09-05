@extends('admin.layouts.app')

@section('page', 'User detail')

@section('content')
<section>
    @php
            $state_area = DB::select('SELECT state, area FROM retailer_list_of_occ GROUP BY area ORDER BY state ASC, area ASC');
        @endphp
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.user.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit</h4>
                        <div class="form-group">
                            <label class="label-control">Prefix<span class="text-danger">*</span> </label>
                            <select class="form-control" name="title">
                                <option value="" hidden selected>Select...</option>
                                <option value="Mr" {{ ($data->title == "Mr") ? 'selected' : '' }}>Mr</option>
                                <option value="Mrs" {{ ($data->title == "Mrs") ? 'selected' : '' }}>Mrs</option>
                                <option value="Miss" {{ ($data->title == "Miss") ? 'selected' : '' }}>Miss</option>
                                <option value="Dr" {{ ($data->title == "Dr") ? 'selected' : '' }}>Dr</option>
                                <option value="CA" {{ ($data->title == "CA") ? 'selected' : '' }}>CA</option>
                                <option value="Prof" {{ ($data->title == "Prof") ? 'selected' : '' }}>Prof</option>
                            </select>
                            @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">First Name <span class="text-danger">*</span> </label>
                            <input type="text" name="fname" placeholder="" class="form-control" value="{{$data->fname}}">
                            @error('fname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Last Name <span class="text-danger">*</span> </label>
                            <input type="text" name="lname" placeholder="" class="form-control" value="{{$data->lname}}">
                            @error('lname') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group ">
                            <label class="label-control"> Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value=" {{$data->name}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">Contact <span class="text-danger">*</span> </label>
                            <input type="number" name="mobile" placeholder="" class="form-control" value="{{$data->mobile}}">
                            @error('mobile') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">WhatsApp Number <span class="text-danger">*</span> </label>
                            <input type="number" name="whatsapp_no" placeholder="" class="form-control" value="{{$data->whatsapp_no}}">
                            @error('whatsapp_no') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Date of Birth <span class="text-danger">*</span> </label>
                            <input type="date" name="dob" placeholder="" class="form-control" value="{{$data->dob}}">
                            @error('dob') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Gender <span class="text-danger">*</span> </label>
                            <select class="form-control" name="gender">
                                <option value="" hidden selected>Select...</option>
                                <option value="male" {{ ($data->gender == "male") ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($data->gender == "female") ? 'selected' : '' }}>Female</option>
                                <option value="trans" {{ ($data->gender == "trans") ? 'selected' : '' }}>Trans</option>
                                <option value="other" {{ ($data->gender == "other") ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="label-control">User Type <span class="text-danger">*</span> </label>
                            <select class="form-control" name="user_type">
                                <option value="" hidden selected>Select...</option>
                                <option value="1" {{ ($data->user_type == "1") ? 'selected' : '' }}>VP</option>
                                <option value="2" {{ ($data->user_type == "2") ? 'selected' : '' }}>RSM</option>
                                <option value="3" {{ ($data->user_type == "3") ? 'selected' : '' }}>ASM</option>
                                <option value="4" {{ ($data->user_type == "4") ? 'selected' : '' }}>ASE</option>
                            </select>
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Employee Id <span class="text-danger">*</span> </label>
                            <input type="text" name="employee_id" placeholder="" class="form-control" value="{{$data->employee_id}}">
                            @error('employee_id') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Address <span class="text-danger">*</span> </label>
                            <input type="text" name="address" placeholder="" class="form-control" value="{{$data->address}}">
                            @error('address') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="floating-label">Landmark <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                value="{{$data->landmark}}" name="landmark">
                            @error('landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="floating-label">State</label>
                                @php
                                    $statesOnly = [];
                                    foreach ($state_area as $stateKey => $stateValue) {
                                        if (!in_array($stateValue->state, $statesOnly)) {
                                            array_push($statesOnly, $stateValue->state);
                                        }
                                    }
                                    $selectedState =  $statesOnly[0];
                                @endphp
                                <select name="state" class="form-control" onchange="cityGenerate($(this).val())">
                                    @foreach ($statesOnly as $statesOnlyvalue)
                                        <option value="{{ $statesOnlyvalue }}"
                                            {{ $selectedState == $statesOnlyvalue ? 'selected' : '' }}>
                                            {{ $statesOnlyvalue }}</option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="floating-label">Area</label>
                                <select name="city" class="form-control"></select>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Pincode <span class="text-danger">*</span> </label>
                            <input type="text" name="pin" placeholder="" class="form-control" value="{{$data->pin}}">
                            @error('pin') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Aadhar Number <span class="text-danger">*</span> </label>
                            <input type="text" name="aadhar_no" placeholder="" class="form-control" value="{{$data->aadhar_no}}">
                            @error('aadhar_no') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Pan Number <span class="text-danger">*</span> </label>
                            <input type="text" name="pan_no" placeholder="" class="form-control" value="{{$data->pan_no}}">
                            @error('pan_no') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="card">
                            <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                            <div class="card-body p-0">
                                <div class="w-100 product__thumb">
                                    <label for="thumbnail"><img id="output" src="{{ asset($data->image) }}" /></label>
                                </div>
                                <input type="file" name="image" id="thumbnail" accept="image/*" onchange="loadFile(event)" class="d-none">
                                <script>
                                    var loadFile = function(event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                            URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                </script>
                            </div>
                            @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update</button>
                            <a type="submit" href="{{ route('admin.user.index') }}" class="btn btn-sm btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
    <script>
        function cityGenerate(state) {
            const statesArr = '<?php echo json_encode($state_area); ?>';
            const parsedStatesArr = JSON.parse(statesArr);

            var city = '';
            $.each(parsedStatesArr, (key, value) => {
                if (value.state == state) {
                    city +=
                        `<option value="${value.area}" >${value.area}</option>`;
                }
            });
            $('select[name="city"]').html(city);
        }
        cityGenerate('{{ $selectedState }}');
    </script>
@endsection
