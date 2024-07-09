@extends('layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.onboard-header')
    <!-- Header Ends -->

    <!-- Account Creation  Starts -->
    <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <h1>Registration</h1>
                <section class="reg-content-main">
                    <div class="reg-navigation-main">
                        <ul class="d-flex align-items-center">
                            <li class="d-flex align-items-center active-first-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">1</small>
                                Signup
                            </li>
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">2</small>
                                Company<br>Information
                            </li>
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">3</small>
                                Business<br>Category
                            </li>
                            <li class="d-flex align-items-center active">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">4</small>
                                Declaration
                            </li>
                            <li class="d-flex align-items-center review active-review">
                                <div class="bg-purple"></div>
                                <span class="verticalLine-active"></span>
                                <small class="round-active"></small>
                                Review<br>Verification
                            </li>
                            <li class="d-flex align-items-center account-creation active">
                                <div class="account-corner-bg"></div>
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">6</small>
                                Account<br>Verification
                            </li>
                            <li class="d-flex align-items-center completion">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">5</small>
                                Account<br>Creation
                            </li>
                            <li class="d-flex align-items-center completion">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">7</small>
                                Completion
                            </li>
                        </ul>
                    </div>
                    
                    <section class="reg-content-sec">
                        @if($company->is_verified == 0)
                        <form action="{{ route('admin.verification.paynow') }}" method="post">
                    @csrf
                    <div class="sub-plans-main">
                        <div class="row">
                            <div class="col-md-8 order-summ-content">
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br> Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                                <div class="row mb-3">
                                    <div class="col-md-12"><span class="mandatory">*All fields are mandatory!</span></div>
                                </div>
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Name <span class="mandatory">*</span></label>
                                            <input name="name" type="text" placeholder="Name" class="form-control" value="{{ $company->name ?? '' }}">
                                            <span class="text-danger">@error('name') {{ $message }} @enderror</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Address</label>
                                            <input name="address" type="text" placeholder="Address" class="form-control" value="{{ $company->address ?? '' }}">
                                            <span class="text-danger">@error('address') {{ $message }} @enderror</span>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PO Box No<span class="mandatory">*</span></label>
                                            <input name="pobox" type="text" placeholder="PO Box No" class="form-control" value="{{ $company->pobox ?? '' }}">
                                            <span class="text-danger">@error('pobox') {{ $message }} @enderror</span>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Location</label>
                                            <input name="location" type="text" placeholder="Location" class="form-control" value="{{ $company->zone ?? '' }}">
                                            <span class="text-danger">@error('location') {{ $message }} @enderror</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Email<span class="mandatory">*</span></label>
                                            <input name="email" type="text" placeholder="Email" class="form-control" value="{{ $company->email ?? '' }}">
                                            <span class="text-danger">@error('email') {{ $message }} @enderror</span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label>Mobile <span class="mandatory">*</span></label>
                                                <div class="d-flex">
                                                    <input name="code" type="text" placeholder="Code" class="form-control mobile-code" value="{{ $company->country_code ?? '' }}">
                                                    <input name="mobile" type="text" placeholder="Mobile" class="form-control mobile-number" value="{{ $company->phone ?? '' }}">
                                                </div>
                                                <span class="text-danger">@error('mobile') {{ $message }} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-amount mt-4">
                                    <h1 class="pb-2">Payment Info</h1>
                                    <div class="d-flex align-items-center justify-content-between total">
                                        Sub Total
                                        <span>$60</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div class="form-group proceed-btn">
                               
                            </div>
                            <div class="form-group proceed-btn">
                                <input type="submit" value="Proceed to Payment" class="btn btn-secondary">
                            </div>
                        </div>
                    </div>
                </form>
                        @elseif($company->is_verified == 2)
                        <div class="row dash-blocks border border-warning">
                            <div class="row p-3">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="ms-4">
                                        <h1 class="mb-0">Account Verification Under Review!</h1>
                                        <p>Your account verification request is under review.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row dash-blocks border border-warning">
                            <div class="row p-3">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="ms-4">
                                        <h1 class="mb-0">Account Verification Completed</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-5 mb-5">
                                <div class="form-group proceed-btn">
                                   
                                </div>
                                <div class="form-group proceed-btn">
                                     <a href="{{ route('admin.adminEdit') }}" class="btn btn-secondary">Proceed</a>
                                </div>
                            </div>
                           
                           
                        </div>
                       
                        @endif
                    </section>
                </section>
            </div>
        </section>
    </div>
@endsection    
                    