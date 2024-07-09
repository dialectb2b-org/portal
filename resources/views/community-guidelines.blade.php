@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/accordian.css') }}" type="text/css">
    <div class="container-fluid p-0 " style="background-color:#21295c">

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

        <section class="container mission-content-main pt-4">
            <div class="sub-plans-head">
                <h1 class="text-start"><a href="{{ url('/') }}" class="back-btn"></a>Community Guidelines</h1>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Tabs content -->
                        <div class="" >
                            <div class="bg-white show active p-5" id="v-pills-introduction" role="tabpanel" aria-labelledby="v-pills-introduction-tab">
                                {!! $content->community_guidelines !!}
                            </div>
                            
                        </div>
                    </div>          
                </section>
            </div>
        </div>



    <footer id="footer2">
        <a href="{{ url('about-us') }}"> About Us</a>   |  
        <a href="{{ url('community-guidelines') }}">Community Guidelines </a>  |   
        <a href="{{ url('faq') }}">FAQ</a>   |    
        <a href="{{ url('privacy-policy') }}">Privacy Policy</a> |    
        <a href="{{ url('user-agreement') }}">User Agreement</a> <br>
        Copyright © {{ date('Y') }} dialectb2b.com. All rights reserved
    </footer>
    
@push('scripts')
<script src="{{ asset('assets/js/accordian.js') }}"  ></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"  ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"  ></script>
@endpush 
@endsection