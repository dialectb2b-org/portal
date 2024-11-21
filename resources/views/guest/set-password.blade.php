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
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">4</small>
                                Create Profile
                            </li>
                            <li class="d-flex align-items-center active">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">5</small>
                                Set Password
                            </li>
                        </ul>
                    </div>

                    <section class="reg-content-sec">
                        <form action="{{ route('member.setPassword') }}" method="post">
                            @csrf
                            <div class="signup-fields pt-5">
                                <div class="row text-center">
                                     <p>Please create a password that:</p>
                                        <p><small> Is at least 8 characters long.</small><br>  
                                           <small> Includes uppercase and lowercase letters.</small><br>  
                                           <small> Contains numbers and special characters like !, @, #, ?, ].</small><br>  
                                           <small> Avoid using < or > in your password.</small></p>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-md-4">
                                        <div class="form-group position-relative">
                                            <label>Enter Password</label>
                                            <input name="password" type="password"  class="form-control">
                                        </div>
                                        <div class="form-group position-relative">
                                            <label>Re-enter Password</label>
                                            <input name="password_confirmation" type="password"  class="form-control">
                                            <div class="invalid-msg2">@error('password'){{ $message }} @enderror</div>
                                        </div>
                                        <div class="row justify-content-center mt-2">
                                            <p><small class="text-center">By clicking Submit, you agree to our
                                                    <a href="{{ url('community-guidelines') }}" target="_blank">Community Guidelines</a>,
                                                    <a href="{{ url('privacy-policy') }}" target="_blank">Privacy Policy</a> and
                                                    <a href="{{ url('user-agreement') }}" target="_blank">User Agreement</a></small></p>
                                        </div>
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
