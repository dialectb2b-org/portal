@php
   $role = auth()->user()->role;
   if($role == 1){
       $extends = 'admin.layouts.app';
       $header = 'admin.layouts.header';
   }
   else if($role == 2){
       $extends = 'procurement.layouts.app';
       $header = 'procurement.layouts.header';
   }
   else if($role == 3){
       $extends = 'sales.layouts.app';
       $header = 'sales.layouts.header';
   }
@endphp

@extends($extends)
@section('content')

    <!-- Header Starts -->
    @include($header)
    <!-- Header Ends -->
    
       <div class="container-fluid reg-bg2">
        <section class="container">
            <div class="row">
               
                <section class="reg-content-main mb-4">
                    <section class="reg-content-sec">
                        
                        <div class="payment-confirm-main">
                            <div class="row mb-3">
                                <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('assets/images/payment-conf-ico.svg') }}" alt="XCHANGE">
                                    <h1 class="d-block">Thank you for your Payment</h1>
                                    <p style="text-align:center">Your account verification process has commenced. You will receive an email notification upon successful completion of the verification process..</p>
                                    <h2>Payment Reference #{{ $subscription->id }}</h2>
                                        <p class="mb-0">The payment confirmation has been sent to </p>
                                            <a href="#">{{ auth()->user()->email }}</a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center d-flex align-items-center">
                            
                                <div class="form-group proceed-btn">
                                    @if($company->is_overlap == 0)
                                    <a href="{{ route('subscription') }}" class="btn btn-secondary">Back to Subscriptions</a>
                                    @else
                                    <a href="{{ route('admin.supersede.accountVerify') }}" class="btn btn-secondary">Proceed</a>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                       
                    </section>

                </section>
            </div>
        </section>
    </div>
    
    
@endsection    
    