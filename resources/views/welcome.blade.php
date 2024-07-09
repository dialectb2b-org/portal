 @extends('layouts.app')
@section('content')

    <div class="container-fluid p-0 login-bg2">

        <header class="navbar">
            <div class="header container-fluid navbar-default d-flex align-items-center">
                <div class="logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
                </div>
                <div class="header-right-btn">
                    <a href="{{ route('member.signUp') }}" class="btn btn-primary float-right ms-2">Individual Signup </a>
                    <a href="{{ route('sign-up') }}" class="btn btn-primary float-right">Organization Signup</a>
                </div>
            </div>
        </header>

        <section class="container-fluid d-flex align-items-center login-sec">
            <div class="login-box">
                <div class="container mt-2 mb-2 px-0">
                    <!-- Main Heading -->

                    <h1 class="justify-content-center d-flex align-items-center">Login</h1>
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif   
                    <div class="p-4">
                        <form class="form-horizontal" action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-group row px-3 ">
                                @if (session('message'))
                                    <small class="alert alert-warning">{{ session('message') }}</small>
                                @endif
                            </div>
                            <!-- Email Input -->
                            <div class="form-group row px-3 position-relative">
                                <i class="email-ico"></i>
                                <input name="email" type="text" placeholder="Email Address" tabindex="1" autofocus="on"
                                    class="form-control border-info placeicon" id="validationCustom03">
                                    <div class="invalid-msg">@error('email'){{ $message }} @enderror</div>
                            </div>
                            

                            <!-- Password Input -->
                            <div class="form-group row px-3 position-relative">
                                <i class="password-ico"></i>
                                <input id="password" name="password" type="password" placeholder="Password" tabindex="2"
                                    class="form-control border-info placeicon mb-4">
                                <i class="eye-ico" id="show-hide"></i>
                                <div class="invalid-msg">@error('password'){{ $message }} @enderror</div>
                            </div>

                            <!-- CheckBox Remember Me-->
                            <div class="form-group row justify-content-center px-1">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input id="customCheck1" type="checkbox" class="custom-control-input ">
                                    <label for="customCheck1" class="custom-control-label">Remember Me</label>
                                </div>
                            </div>

                            <!-- Log in Button -->
                            <div class="form-group row justify-content-center">
                                <input type="submit" value="Login" class="btn btn-primary" tabindex="3">
                            </div>

                            <!-- Forgot Password Link -->
                            <div class="row">
                                <a href="{{ route('password.forgot') }}" class="forgot-pass" tabindex="4">Forgot Password?</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="our-moto">
                <p class="position-content">
                    <a href="#"> Dialectb2b.com</a> - The Voice of Your Hunting and Sourcing. Join us and ignite your business prosperity with fresh sales leads and 
                    trustworthy partnerships! 
                    
                </p>
            </div>
            
        </section>
        <a href="#second" class="down-arrow d-flex"></a>
    </div>

    <section class="home2-content-main" id="second">
        <section class="container">
        <div class="row">
            <div class="col-md-5">
                <img src="{{ asset('assets/images/we-are-img.png') }}" width="100%" alt="">
            </div>
            <div class="col-md-7 pt-2">
                <h1>Welcome to</h1>
                <h2 class=" pt-4">Dialectb2b.com</h2>
                <p class=" pt-5">
                Your Premier Online Platform for Transformative Sales and Procurement Solutions.
                </p>
                <p>At Dialectb2b.com, we understand the ever-evolving nature of the contemporary business landscape. Our mission is to redefine how Sales and Procurement Professionals operate across diverse industries and businesses of all sizes. We firmly believe that efficiency, transparency and innovation form the bedrock of success in the modern world of commerce.</p>
                <div class="d-flex justify-content-end ">
                    <a href="{{ url('about-us') }}" class="read-more2">Read More</a>
                </div>
            </div>
        </div>


        <section class="mt-5">
            <h3 class="mb-3">Sales</h3>
            <div class="d-flex flex-wrap">
               
                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/automated-ico.svg') }}" alt="">
                    <h3 class="mt-3">Automated</h3>
                    <p class="mt-2">Digital Channel to Receive Qualified Leads</p>
                </div>
                
                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/cost-effective-ico.svg') }}" alt="">
                    <h3 class="mt-3">Cost-effective</h3>
                    <p class="mt-2">Reduce operational cost for Sales</p>
                </div>


                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/always-on-ico.svg') }}" alt="">
                    <h3 class="mt-3">Always on</h3>
                    <p class="mt-2">Able to Open 24 hours x 7 days</p>
                </div>

                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/prospecting-ico.svg') }}" alt="">
                    <h3 class="mt-3">Prospecting</h3>
                    <p class="mt-2">Prospects for new customers</p>
                </div>


                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/expand-reach-ico.svg') }}" alt="">
                    <h3 class="mt-3">Expand Reach</h3>
                    <p class="mt-2">Access to a Large Market</p>
                </div>

            </div>
        </section>


        <section class="mt-5">
            <h3 class="mb-3">Procurement</h3>
            <div class="d-flex flex-wrap">
                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/precise-ico.svg') }}" alt="">
                    <h3 class="mt-3">Precise</h3>
                    <p class="mt-2">Accurate internal needs’ analysis</p>
                </div>

                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/ethical-ico.svg') }}" alt="">
                    <h3 class="mt-3">Ethical</h3>
                    <p class="mt-2">Able to avoid conflict of interest</p>
                </div>


                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/diversified-ico.svg') }}" alt="">
                    <h3 class="mt-3">Diversified</h3>
                    <p class="mt-2">Enablement of multiple suppliers</p>
                </div>

                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/resourceful-ico.svg') }}" alt="">
                    <h3 class="mt-3">Resourceful</h3>
                    <p class="mt-2">Able to Reduce talent shortages</p>
                </div>


                <div class="sales-boxes d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/streamlined-ico.svg') }}" alt="">
                    <h3 class="mt-3">Streamlined</h3>
                    <p class="mt-2">Reduce mutual procurement process</p>
                </div>

            </div>
        </section>

        </section>
    </section>
       <footer id="footer2">
        <a href="{{ url('about-us') }}"> About Us</a>   |   <a href="{{ url('community-guidelines') }}">Community Guidelines </a>  |    <a href="{{ url('faq') }}">FAQ</a>   |    <a href="{{ url('privacy-policy') }}">Privacy Policy</a> |    <a href="{{ url('user-agreement') }}">User Agreement</a> <br>
        Copyright © {{ date('Y') }} dialectb2b.com. All rights reserved
    </footer>
        @push('scripts')
    <script>
        $('#show-hide').on('click', function() {
             if($('#password').attr('type') === 'password'){
                $('#password').attr('type','text')
             } 
             else{
                $('#password').attr('type','password')
             }
        });
    </script>
    @endpush
@endsection