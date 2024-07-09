@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->


 <!-- Main Content -->
        <section class="container-fluid pleft-77">
            <div class="px-4 py-3">
                <h1>Profile Settings</h1>
                <div class="profile-stting-sec-main edit-fields-main">
                    <div class="row">
                        <div class="col-md-3 pr-0">
                            <div class="profile-stting-sec1">
                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="{{ route('admin.editProfile') }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
                                </div>
                                <div class="d-flex">
                                    <div class="tumb-img d-flex align-items-center justify-content-center"><img src="{{ $user->profile_image != '' ? asset($user->profile_image) : ''  }}">
                                    </div>
                                    <div class="sale-prof-name">
                                        <h2>{{ $user->name }}</h2>
                                        <span>{{ $user->designation }}</span>
                                        <div class="form-group">
                                            <input type="submit" value="Reset Password" class="btn btn-third"  onclick="window.location.href = '#'">
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-prof-detail">
                                    <label>Email</label>
                                    {{ $user->email }}
                                </div>
                                <div class="sale-prof-detail">
                                    <label>Phone</label>
                                    {{ $user->country_code }} {{ $user->mobile }}
                                </div>
                                <div class="sale-prof-detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <label>Landline</label>
                                        {{ $user->country_code }} {{ $user->landline }}
                                    </div>
                                    <div>
                                        <label>Extension</label>
                                        {{ $user->extension }}
                                    </div>
                                </div>
                            </div>

                            <div class="profile-stting-sec2">
                                <div class="d-flex mb-4">
                                    <h1>Company Info</h1>
                                </div>
                                <div class="d-flex">
                                    <div class=" d-flex align-items-center justify-content-center"><img src="{{ asset($company->logo) }}" height="50px">
                                    </div>
                                    <div class="company-prof-name">
                                        <span>{{ $company->name }}</span>
                                        <a href="#">{{ $company->domain }}</a>
                                    </div>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Address</label>
                                            {{ $company->address }}
                                        </div>
                                        <div class="col-md-3">
                                            <label>Zone</label>
                                            {{ $company->zone }}
                                        </div>
                                        <div class="col-md-3">
                                            <label>Street</label>
                                            {{ $company->street }}
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Building</label>
                                            {{ $company->building }}
                                        </div>
                                        <div class="col-md-3">
                                            <label>Unit</label>
                                            {{ $company->unit }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3  pl-0 pr-0">
                            <div class="profile-stting-sec1">
                                <div class="d-flex mb-2">
                                    <h1>Documents</h1>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Document</label>
                                            {{ $company->document->document->name ?? '' }}
                                        </div>
                                        <div class="col-md-5">
                                            <label>Document No</label>
                                            {{ $company->document->doc_number ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Expiry Date</label>
                                            {{ $company->document->expiry_date ?? '' }}
                                        </div>
                                        <div class="col-md-5">
                                            <label>Status</label>
                                            @php $expiry =  $company->document->expiry_date ?? ''; @endphp
                                            @if($expiry > today())
                                                Active
                                            @else
                                                Expired
                                            @endif
                                        </div>
                                    </div>
                                    <a href="#" class="mt-4 d-flex open_doc">View Document</a>
                                </div>
                            </div>
                            <div class="profile-stting-sec3">
                                <div class="d-flex mb-2">
                                    <h1>Declaration</h1>
                                </div>
                                <div class="sale-prof-detail">
                                    <a href="#" class="mt-4 d-flex open_declaration">View Declaration</a>
                                </div>
                            </div>
                            <div class="profile-stting-sec3">
                                <div class="d-flex mb-2">
                                    <h1>Account Verification</h1>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Status:</label>
                                            {{ $company->is_verified == 1 ? 'Verified' : 'Non-Verified' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-stting-sec3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h1>Subscription</h1>
                                    <div class="form-group">
                                        @if($company->current_plan == 1)
                                        <input type="submit" value="Upgrade" class="btn btn-third" onclick="window.location.href = '{{ route('subscription.plans') }}'" >
                                        @else
                                            @if($company->current_plan_status == 1)
                                            <a  class="btn btn-third cancel_sub" >Unsubscribe</a>
                                            @else
                                            <p class="text-danger">Plan Unsubscribed</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Current Package:</label>
                                            {{ $package->name ?? '' }}
                                        </div>
                                        <div class="col-md-5">
                                            <label>End Date</label>
                                            {{ $company->plan_validity != null ? $company->plan_validity : 'NA' }}
                                        </div>
                                    </div>
                                    <div class="sale-prof-detail">
                                        @if($company->current_plan == 2)
                                        <a href="{{ route('subscription') }}" class="mt-4 d-flex">View Details</a>
                                        @endif
                                    </div>
                                    @if($company->current_plan_status == 2)
                                    <p class="text-warning">Note :- You unsubscribe request has been received. your plan will be cancelled on {{ $company->plan_validity ?? '' }}.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6  pl-0 pr-0">
                            <div class="profile-stting-sec4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h1>Business Categories</h1>
                                    <div class="form-group">
                                        <input type="submit" value="Unsubscribe" class="btn btn-third"  onclick="window.location.href = ''">
                                        <input type="submit" value="Purchase" class="btn btn-secondary mx-2" onclick="window.location.href = 'purchsase-business-category.html';">
                                    </div>

                                </div>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                               

                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h2 class="mb-3">Free</h2>
                                            <ul class="right-categories-list">
                                                @foreach($company->activities as $key => $activity)
                                                <li><a href="#">AC Equipment &amp; AC System Repairs</a></li>
                                                @endforeach
                                                
                                           </ul>
                                           <p>*You will not be able to unsubscribe free categories.</p>
                                        </div>
                                       
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <div class="d-flex justify-content-between">
                                                <h2 class="mb-2">Subscribed</h2>
                                                <a href="#">Billing History</a>
                                            </div>
                                            <ul class="right-categories-list-subscribed ">
                                                <li class="valid-till d-flex justify-content-between align-items-center">
                                                    <div>District Cooling & Heating System<br>
                                                    <span>Valid till: 26 Oct 2023</span>
                                                    </div>
                                                    <a href="#">Renew Now</a>
                                                </li>
                                                <li class="expired-on d-flex justify-content-between align-items-center">
                                                    <div>Duct Manufacturers, Duct Cleaners & Accessories<br>
                                                    <span>Valid till: 26 Oct 2024</span>
                                                    </div>
                                                    <a href="#">Renew Now</a>
                                                </li>
                                                
                                                <li>
                                                    <div>HVAC Contractors<br>
                                                        <span>Valid till: 26 Jan 2024</span>
                                                        </div>
                                                </li>
                                           </ul>
                                        </div>
                                       
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>  



            </div>
        </div>

    </section>

   
    <!-- End Main Content -->

    <!-- View Document Model -->
    <div class="modal fade bd-example-modal-lg" id="viewDocumentModel" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Document</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ asset($company->document->doc_file ?? '') }}" class="license-preview d-flex align-items-center justify-content-center">
                </div>
            </div>
        </div>
    </div>

        <!-- View Declaration Model -->
        <div class="modal fade bd-example-modal-lg" id="viewDeclarationModel" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLongTitle">Declaration</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <embed src="{{ asset($company->decleration ?? '') }}" class="license-preview d-flex align-items-center justify-content-center">
                    </div>
                </div>
            </div>
        </div>
        
       <!-- View Cancel Subscription Model -->
        <div class="modal fade bd-example-modal-lg" id="cancelSubscriptionModel" tabindex="-1" role="dialog" aria-labelledby="cancelSubscriptionModel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLongTitle">Unsubscribe</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <div class="card">
                            <div class="card-body">
                                <form action="{{ route('subscription.cancel') }}" method="post">
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="sale-prof-detail">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label>Current Plan</label>
                                                    {{ $package->name ?? '' }}
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Valid Upto</label>
                                                     {{ $company->plan_validity != null ? $company->plan_validity : 'NA' }}
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Reason for Cancellation</label>
                                                        <textarea class="form-control" name="reason" required></textarea>
                                                    </div>
                                                    <div class="col-md-12 mt-4 d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-third">Cancel Subscription</button>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $('body').on('click','.open_doc',function(){
        $('#viewDocumentModel').modal('show');
    });

    $('body').on('click','.close',function(){
        $('#viewDocumentModel').modal('hide');
        $('#viewDeclarationModel').modal('hide');
    });

    $('body').on('click','.open_declaration',function(){
        $('#viewDeclarationModel').modal('show');
    });
    
       $('body').on('click','.cancel_sub',function(){
        $('#cancelSubscriptionModel').modal('show');
    });

   
</script>    
@endpush
 
@endsection    