@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->

    <!-- Account Creation  Starts -->
    <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <section class="reg-content-main">
                    <section class="reg-content-sec">
                        <div class="signup-fields">
                            <div class="row mt-4">
                                <div class="col-md-12 setup-accnt">
                                    <h2>Setup your account</h2>
                                    <ul>
                                        <li>
                                            <div class="acc-list-main d-flex justify-content-between align-items-center justify-content-center">
                                                <span class="sales-accnt">Add Sales Account</span>
                                            </div>
                                            <form action="{{ route('admin.staff.createSalesAccount') }}" method="post">
                                                @csrf
                                                <div class="expand-setup-accnt">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12"><span class="mandatory">*All fields are mandatory!</span></div>
                                                    </div>
                                                    <div class="row">
                                                        <input type="hidden" name="user_id" value="{{ $sales->id ?? '' }}" />
                                                        <input type="hidden" name="role" value="3" />
                                                        <div class="col-md-4">
                                                            <div class="input-group position-relative">
                                                                <label>Name  <span class="mandatory">*</span></label>
                                                                <input id="name" type="text" name="name" value="{{ old('name') ?? $sales->name ?? '' }}" placeholder="Name" class="form-control @error('name') red-border @enderror" autofocus>
                                                                <div class="invalid-msg2">@error('name'){{ $message }}@enderror</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group position-relative">
                                                                <label>Designation<span class="mandatory">*</span></label>
                                                                <input id="designation" type="text" name="designation" value="{{ old('designation') ?? $sales->designation ?? '' }}" placeholder="Designation" class="form-control @error('designation') red-border @enderror">
                                                                <div class="invalid-msg2">@error('designation'){{ $message }}@enderror</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group position-relative">
                                                                <label>Email<span class="mandatory">*</span></label>
                                                                <input id="email" type="text" name="email" value="{{ old('email') ?? $sales->email ?? '' }}" placeholder="Email" class="form-control @error('email') red-border @enderror">
                                                                <div class="invalid-msg2">@error('email'){{ $message }}@enderror</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Mobile </label>
                                                            <div class="d-flex">
                                                                <input type="text" value="{{ $country->phonecode }}"  class="form-control mobile-code" readonly>
                                                                <input id="mobile" type="text" name="mobile" value="{{ old('mobile') ?? $sales->mobile ?? '' }}" placeholder="Mobile" class="form-control mobile-number @error('mobile') red-border @enderror">
                                                            </div>
                                                            <div class="invalid-msg2">@error('mobile'){{ $message }}@enderror</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Land LIne </label>
                                                            <div class="d-flex">
                                                                <input type="text" value="{{ $country->phonecode }}" class="form-control mobile-code" readonly>
                                                                <input id="landline" type="text" name="landline" value="{{ old('landline') ?? $sales->landline ?? '' }}" placeholder="Landline" class="form-control mobile-number @error('landline') red-border @enderror">
                                                            </div>
                                                            <div class="invalid-msg2">@error('landline'){{ $message }}@enderror</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Extension</label>
                                                            <input id="extension" type="text" name="extension" value="{{ old('extension') ?? $sales->extension ?? '' }}" placeholder="Extension" class="form-control @error('extension') red-border @enderror">
                                                            <div class="invalid-msg2">@error('extension'){{ $message }}@enderror</div>
                                                        </div>

                                                        <div class="d-flex justify-content-end">
                                                            <button type="submit" class="save-continue-btn">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                </section>
            </div>
        </section>
    </div>
    <!-- Account Creation End -->

@endsection
 

   