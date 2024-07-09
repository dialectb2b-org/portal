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
   else if($role == 4){
       $extends = 'member.layouts.app';
       $header = 'member.layouts.header';
   }
@endphp

@extends($extends)
@section('content')
    <!-- Header Starts -->
        @include($header)
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
                                    <a href="{{ route('profile.edit') }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
                                </div>
                                <div class="d-flex">
                                    <div class="tumb-img d-flex align-items-center justify-content-center"><img src="{{ $user->profile_image != '' ? asset($user->profile_image) : ''  }}">
                                    </div>
                                    <div class="sale-prof-name">
                                        <h2>{{ $user->name }}</h2>
                                        <span>{{ $user->designation }}</span>
                                        <div class="form-group">
                                            <a  class="btn btn-third"  href="{{ route('profile.changePassword') }}">Reset Password</a>
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
                                <div class="d-flex justify-content-between mb-4">
                                    <h1>Company Info</h1>
                                    <a href="{{ route('companyProfile.index') }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
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
                                        <div class="col-md-12">
                                            <label>Address</label>
                                            {{ $company->address }} 
                                            {{ $company->zone != '' ? ', '.$company->zone : ''  }}
                                            {{ $company->street != '' ? ', '.$company->street : ''  }}
                                            {{ $company->building != '' ? ', '.$company->building : ''  }}
                                            {{ $company->unit != '' ? ', '.$company->unit : ''  }}
                                        </div>
                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3  pl-0 pr-0">
                            <div class="profile-stting-sec1">
                                <div class="d-flex justify-content-between mb-2">
                                    @php $expiry =  $company->document->expiry_date ?? ''; @endphp
                                    <h1>Documents</h1>
                                    @if($expiry < today())
                                    <a href="#" class="team-edit edit-document"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
                                    @endif
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
                                            @if($expiry > today())
                                                Active
                                            @else
                                                Expired
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <a href="#" class="mt-4 d-flex open_doc">View Document</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="profile-stting-sec3">-->
                                <!--<div class="d-flex mb-2">-->
                                <!--    <h1>Declaration</h1>-->
                                <!--</div>-->
                                <!--<div class="sale-prof-detail">-->
                                <!--    <a href="#" class="mt-4 d-flex open_declaration">View Declaration</a>-->
                                <!--</div>-->
                            <!--</div>-->
                            <div class="profile-stting-sec3">
                                <div class="d-flex mb-2">
                                    <h1>Account Verification</h1>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Status</label>
                                            {{ $company->is_verified == 1 ? 'Verified' : 'Non-Verified' }}
                                        </div>
                                        <div class="col-md-6">
                                            @if($company->is_verified == 1)
                                            <a href="{{ route('subscription') }}" class="mt-4 d-flex">View Details</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-stting-sec3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h1>Subscription</h1>
                                    <div class="form-group">
                                        @if($company->current_plan == 1)
                                            @if(auth()->user()->role == 1)
                                            <input type="submit" value="Upgrade" class="btn btn-third" onclick="window.location.href = '{{ route('subscription.plans') }}'" >
                                            @endif
                                        @else
                                            @if(auth()->user()->role == 1)
                                                @if($company->current_plan_status == 1)
                                                <a  class="btn btn-third cancel_sub" >Unsubscribe</a>
                                                @else
                                                <p class="text-danger">Plan Unsubscribed</p>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Current Package</label>
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
                                        @if(auth()->user()->role == 3)
                                        <a href="{{ route('category-purchase.unsubsubscribeCategory') }}"  class="btn btn-third" >Unsubscribe</a>
                                        <a href="{{ route('category-purchase.index') }}" class="btn btn-secondary mx-2">Purchase</a>
                                        @endif
                                    </div>

                                </div>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                               

                                <div class="sale-prof-detail">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h2 class="mb-3">Privilege Category</h2>
                                            <ul class="right-categories-list">
                                                @foreach($company->activities as $key => $activity)
                                                <li><a href="#">{{ $activity->name }}</a></li>
                                                @endforeach
                                                
                                           </ul>
                                           <p>*You will not be able to unsubscribe free categories.</p>
                                        </div>
                                       
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <div class="d-flex justify-content-between">
                                                <h2 class="mb-2">Subscribed</h2>
                                                <a href="{{ route('subscription') }}" class="mt-4 d-flex">View Details</a>
                                            </div>
                                            <ul class="right-categories-list-subscribed " style="overflow-y: scroll;max-height: 350px;">
                                                @foreach($company->paid_activities as $key => $paid_activity)
                                                @php
                                                    $expiryDate = \Carbon\Carbon::parse($paid_activity->pivot->expiry_date);
                                                    $currentDate = \Carbon\Carbon::now();
                                                    $isOneWeekAway = $expiryDate->diffInDays($currentDate) == 7 && $expiryDate->isAfter($currentDate);
                                                    $isNotExpired = $expiryDate->isFuture();
                                                    if($isOneWeekAway && $isNotExpired){
                                                        $status = "valid-till";
                                                    }
                                                    elseif(!$isNotExpired){
                                                        $status = "expired-on";
                                                    }
                                                    else{
                                                        $status = null;
                                                    }
                                                @endphp
                                                <li class="{{ $status }} d-flex justify-content-between align-items-center">
                                                    <div>{{ $paid_activity->name }}<br>
                                                    @if( $paid_activity->pivot->status == 1)
                                                    <span>Valid till: {{ $paid_activity->pivot->expiry_date ?? '' }}</span>
                                                    @else
                                                     <span>Unsubscribed</span>
                                                    @endif
                                                    </div>
                                                    <!--<a href="#">Renew Now</a>-->
                                                </li>
                                                @endforeach
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Document</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="width:100%;height:400px;overflow-y:scroll">
                    <a href="{{ asset($company->document->doc_file ?? '') }}" target="_blank">
                        <embed src="{{ asset($company->document->doc_file ?? '') }}" class="d-flex align-items-center justify-content-center" >
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Document Model -->
    <div class="modal fade bd-example-modal-lg" id="editDocumentModel" tabindex="-1" role="dialog" aria-labelledby="editDocumentModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Update Document</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.updateDocument') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mx-auto mb-4">
                                <div class="form-group position-relative">
                                    <label>Upload Document<span class="mandatory">*</span></label>
                                    <input id="file" name="file" type="file" value="" class="form-control website" required>
                                    <small class="text-danger">@error('file'){{ $message }}@enderror</small>
                                </div>
                            </div>
                            <div class="col-md-12 mx-auto mb-4">
                                <div class="form-group position-relative">
                                    <label>Expiry Date<span class="mandatory">*</span></label>
                                    <input id="expiry_date" name="expiry_date" type="date" value="" placeholder="Expiry Date" class="form-control website" required>
                                    <small class="text-danger">@error('expiry_date'){{ $message }}@enderror</small>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <input type="submit" value="Save" class="btn btn-secondary">
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- View Declaration Model -->
        <div class="modal fade" id="viewDeclarationModel" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLongTitle">Declaration</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <embed src="{{ asset($company->decleration ?? '') }}" class="d-flex align-items-center justify-content-center" style="width:100%;height:700px;">
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
    
    $('body').on('click','.edit-document',function(){
        $('#editDocumentModel').modal('show');
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