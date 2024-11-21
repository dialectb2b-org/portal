@extends('layouts.app')
@section('content')
<div class="container-fluid p-0 login-bg">

    <header class="navbar">
        <div class="header container-fluid navbar-default d-flex align-items-center">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
            </div>
        </div>
    </header>

        
    <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <h1>Registration</h1>
                <section class="reg-content-main">
                    <div class="reg-navigation-main team-sign-nav-main">
                        <ul class="d-flex align-items-center">
                            <li class="d-flex align-items-center active-first-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">1</small>
                                Confirm Company Registration
                            </li>
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">2</small>
                                Validate Email
                            </li>
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">3</small>
                                Accept Declaration
                            </li>
                            <li class="d-flex align-items-center active">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">4</small>
                                Create Profile
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">5</small>
                                Set Password
                            </li>
                        </ul>
                    </div>
                    
                    <section class="reg-content-sec">
                        <form action="{{ route('team.onboard.collectProfile') }}" method="post">
                            @csrf
                        <div class="signup-fields">
                            <div class="row mb-3">
                                <div class="col-md-12"><span class="mandatory">*All fields are mandatory!</span></div>
                            </div>
                        
                            <div class="row">
                                <input type="hidden" name="role" value="4" />
                                <div class="col-md-4">
                                    <div class="input-group position-relative">
                                        <label>Name  <span class="mandatory">*</span></label>
                                        <input id="name" type="text" name="name" value="{{ old('name') ?? $user['name'] ?? '' }}" placeholder="Name" class="form-control @error('name') red-border @enderror" autofocus>
                                        <div class="invalid-msg2">@error('name'){{ $message }}@enderror</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group position-relative">
                                        <label>Designation<span class="mandatory">*</span></label>
                                        <input id="designation" type="text" name="designation" value="{{ old('designation') ?? '' }}" placeholder="Designation" class="form-control @error('designation') red-border @enderror">
                                        <div class="invalid-msg2">@error('designation'){{ $message }}@enderror</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group position-relative">
                                        <label>Email<span class="mandatory">*</span></label>
                                        <input id="email" type="text" name="email" value="{{ old('email') ?? $user['email'] ?? '' }}" readonly placeholder="Email" class="form-control @error('email') red-border @enderror">
                                        <div class="invalid-msg2">@error('email'){{ $message }}@enderror</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Mobile </label>
                                    <div class="d-flex">
                                        <input type="text" value="+974" placeholder="+974" class="form-control mobile-code" readonly>
                                        <input id="mobile" type="text" name="mobile" value="{{ old('mobile') ?? '' }}" placeholder="Mobile" class="form-control mobile-number @error('mobile') red-border @enderror">
                                    </div>
                                    <div class="invalid-msg2">@error('mobile'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label>Land LIne </label>
                                    <div class="d-flex">
                                        <input type="text" value="+974" placeholder="+974" class="form-control mobile-code" readonly>
                                        <input id="landline" type="text" name="landline" value="{{ old('landline') ?? $procurement->landline ?? '' }}" placeholder="Landline" class="form-control mobile-number @error('landline') red-border @enderror">
                                    </div>
                                    <div class="invalid-msg2">@error('landline'){{ $message }}@enderror</div>
                                </div>
                                <div class="col-md-4">
                                    <label>Extension</label>
                                    <input id="extension" type="text" name="extension" value="{{ old('extension') ?? '' }}" placeholder="Extension" class="form-control @error('extension') red-border @enderror">
                                    <div class="invalid-msg2">@error('extension'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="form-group proceed-btn">
                                <input type="submit" value="Proceed" class="btn btn-secondary">
                            </div>
                        </div>
                    </form>
                    </section>

                </section>
            </div>
        </section>
    </div>
</div>        

@endsection