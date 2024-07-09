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
                            <div class="px-4 py-3">
                                    <h1 class="mb-4 mt-2">Account Verification</h1>
                                    <form action="" method="post">
                                        <div class="sub-plans-main">
                                            <div class="row">
                                                <div class="col-md-12 order-summ-content">
                                                    <p>Value of getting verified with Dialectb2b.com</p>
                                                    <div class="mt-2">
                                                        <div class="row">
                                                       <p>
                                                           The verified badge on Dialectb2b.com provides tangible benefits beyond mere symbolism. It empowers your company with increased credibility, visibility, and protection against impersonation, ultimately contributing to a more secure and trusted online environment.</p>
                    
                    <p>Boosted Credibility: The verified badge on Dialectb2b.com is more than just a status symbol; it's a testament to your credibility. By having a verified account, you showcase authenticity and trustworthiness to your audience and potential collaborators. This heightened credibility can lead to stronger connections, increased client trust, and an enhanced reputation within the industry.</p>
                    
                    <p>Enhanced Visibility: A verified account enjoys increased visibility on Dialectb2b.com. It stands out in search of results and recommendations, making it easier for clients and partners to find and engage with your company. This heightened visibility can transform into more opportunities, partnerships, and business growth.</p>
                    
                    <p>Protection Against Impersonation: Verification on Dialectb2b.com offers robust protection against impersonation and fraudulent activities. By verifying your account, you mitigate the risk of malicious entities creating fake accounts in your company's name. This safeguard not only protects your brand integrity, but also ensures a secure online environment for your clients and collaborators.</p>
                    
                    <p>Improved Risk Management: Verification serves as a key component of risk management. It helps in identifying genuine entities from potential imposters, reducing the likelihood of falling victim to scams or deceptive practices. This proactive approach to risk mitigation showcases your commitment to maintaining a safe and trustworthy online presence of all stakeholders involved.</p>
                                                       </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-5">
                                                <div class="form-group proceed-btn">
                                                   
                                                </div>
                                                <div class="form-group proceed-btn">
                                                    <a href="{{ route('subscription.plans') }}" class="btn btn-secondary">Proceed</a>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
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
                    