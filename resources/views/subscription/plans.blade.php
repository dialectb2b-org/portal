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
    
      <section class="container-fluid pleft-77">
        <div class="px-4 py-3">
            
            <div class="sub-plans-head mt-2 mb-3 d-flex align-items-center justify-content-between">
                <div>
                    <h1 style="color:#20285B;"><a href="{{ route('subscription') }}" class="back-btn" style="margin-left: 11px;"></a>Dialectb2b.com Pricing & Plans</h1>
                    @if($company->current_plan == 1)
                        <label>Your current subscription is Basic and you can upgrade to Standard for additional benefits.</label>
                    @endif
                </div>

                <div class="d-flex tab-btns-main">
                    <a href="{{ route('subscription') }}" class="btn tablinks7 btn-forth active">Portal Subscription</a>
                    <a href="{{ route('category-purchase.index') }}" class="btn tablinks7 btn-forth ">Category Subscription</a>
                </div>
            </div>
            <form action="{{ route('subscription.billingSummary') }}" method="get">
                @csrf
            <div class="sub-plans-main">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="sub-plan-2">
                            <div class="sub-plan-list-head3">
                                @if($company->current_plan == 1)
                               <div class="plan-off d-flex">
                                    <span>
                                        <span>Billed</span>
                                        Monthly
                                    </span>
                                        <input type="checkbox" name="period" id="aa3" class="add-page-check" value="12" checked>
                                        <label for="aa3" class="d-flex mt-4"></label>
                                    <div class="month-year-switch">
                                        <span class="pad-top-22">
                                            Yearly
                                            <label>{{ $portal_settings->plan_discount }}% OFF</label>
                                        </span>
                                    </div>
                               </div>
                               <i class="pt-3 d-block">Save {{ $portal_settings->plan_discount }}% when you pay yearly</i>
                               @endif
                            </div>
    
                            <div class="basic-plan-list">
                                <h2>Sales Account</h2>
                                <ul class="mt-0">
                                    <li class="d-flex align-items-center">Receiving Business Enquiry</li>
                                    <li class="d-flex align-items-center">Responding to Business Enquiry
                                    </li>
                                    <li class="d-flex align-items-center">Limited Vendor participation</li>
                                    <li class="d-flex align-items-center">FAQ Access for Enquiry</li>
                                    <li class="d-flex align-items-center">View Account History</li>
                                </ul>
    
                                <h2>Procurement Account</h2>

                                <ul class="mt-0">
                                    <li class="d-flex align-items-center">RFQ Generation</li>
                                    <li class="d-flex align-items-center">Proposal Reception</li>
                                    <li class="d-flex align-items-center">Generate Limited Vendor RFQ</li>
                                    <li class="d-flex align-items-center">Bid Review</li>
                                    <li class="d-flex align-items-center">Viewing Account History</li>
                                </ul>
    
                                <h2>Team Account</h2>
                                <ul class="mt-0">
                                    <li class="d-flex align-items-center">RFQ Generation</li>
                                    <li class="d-flex align-items-center">Proposal Reception</li>
                                    <li class="d-flex align-items-center">Generate Limited Vendor RFQ</li>
                                    <li class="d-flex align-items-center">Bid Review</li>
                                    <li class="d-flex align-items-center">View Account History</li>
                                </ul>
    
                            </div>
    
                        </div>
                    </div>
                
                <div class="col-md-4">
                    <div class="sub-plan-1">
                        <div class="sub-plan-list-head2">
                            <div class="busins-head d-flex justify-content-between">
                            <h1 class="plan2">Basic</h1>
                            </div>

                            <div class="busins-head-count d-flex">
                                <span class="grey-txt">Now Free</span>
                            </div>
                            <div class="form-group">
                                @if($company->current_plan == 2)
                                    @if($company->current_plan_status == 1)
                                        <button name="plan" type="submit" class="btn btn-third-color border-0" value="1" style="margin-top: 8px;">Switch to Basic</button>
                                    @else
                                        <input  type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0" value="1" style="margin-top: 8px;">
                                    @endif
                                @else
                                <input name="plan" type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0" value="1" style="margin-top: 8px;">
                                @endif
                            </div>

                        </div>

                        <div class="sub-plan-list-content">
                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Daily 1 credit and will expire same day 
                                </li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>4 requests in a month </li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No FAQ</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Last two months</li>
                            </ul>

                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Only 5 Nos. per month</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Only 15 Review per month</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Last two months</li>
                            </ul>


                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Only 5 Nos. per month</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Only 15 Review per month</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>Last two months</li>
                            </ul>

                            <div class="form-group px-4 pt-4">
                                @if($company->current_plan == 2)
                                    @if($company->current_plan_status == 1)
                                        <button name="plan" type="submit" class="btn btn-third-color border-0" value="1">Switch to Basic</button>
                                    @else
                                        <input  type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0" value="1">
                                    @endif
                                @else
                                <input name="plan" type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0" value="1">
                                @endif
                            </div>

                        </div>

                    </div>
                </div>


                <div class="col-md-4">
                    <div class="sub-plan-1">
                        <div class="sub-plan-list-head">
                            <div class="busins-head d-flex justify-content-between">
                            <h1>Standard</h1>
                            @if($company->current_plan == 1)
                            <div class="popular">Popular</div>
                            @endif
                            </div>
                            <input id="total" name="total" type="hidden" value="60"/>
                            <input id="discount" name="discount" type="hidden" value="{{ $portal_settings->plan_discount ?? 0 }}"/>
                            <div class="busins-head-count d-flex">
                                 @if($company->current_plan == 1)
                                <span>$<span class="sub-total-val">60</span></span>
                                <h2>per month</h2>
                                @endif
                            </div>
                            <div class="form-group">
                                @if($company->current_plan == 1)
                                <button name="plan" type="submit" class="btn btn-third-color border-0" value="2">Upgrade</button>
                                @else
                                    @if($company->current_plan_status == 1)
                                        <input type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0">
                                    @else
                                         <label class="text-white">Your subscription switched to Basic, but you can avail Standard features till  {{ \Carbon\Carbon::parse($company->plan_validity)->format('d-m-Y') }}.</label>
                                    @endif
                                @endif
                            </div>

                        </div>

                        <div class="sub-plan-list-content">
                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>FAQ Open</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>Open</li>
                            </ul>

                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>FAQ Open</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>Open</li>
                            </ul>


                            <ul>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="grey-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>No Limitation</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>FAQ Open</li>
                                <li class="d-flex align-items-center"><i class="green-tick"></i>Open</li>
                            </ul>

                            <div class="form-group px-4 pt-4">
                                @if($company->current_plan == 1)
                                <button name="plan" type="submit" class="btn btn-third-color border-0" value="2">Upgrade</button>
                                @else
                                    @if($company->current_plan_status == 1)
                                        <input type="disabled" value="Current Subscription" class="btn btn-fourth-color border-0">
                                    @else
                                        <input  type="disabled" value="" class="btn btn-fourth-color border-0" value="1">
                                    @endif
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
                
                </div>
            </div>
            </form>
        </div>

    </section>
    @push('scripts')
    <script>
        $(document).ready(function() {
         setTimeout(function () {
            checkSubtot();
         },1000);
    });

    
    $('.add-page-check').on('change',function(){
        checkSubtot();
    });
    
    function checkSubtot(){
        var total = $('#total').val();
        var discount_per = 0;
        //$('.add-page-check').change(function() {
            if ($('.add-page-check').is(':checked')) {
                var plan_value = total;
                discount_per = $('#discount').val();
                var discountAmount = (discount_per / 100) * plan_value;
                var subtot = plan_value - discountAmount;
                $('.sub-total-val').text(subtot.toFixed(0));
            } else {
                var subtot = total * 1;
                $('.sub-total-val').text(subtot.toFixed(0));
            }
       // });
    }  
      
    </script>
    @endpush
@endsection