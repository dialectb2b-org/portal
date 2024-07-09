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
    <!-- Main Content -->
    <section class="container-fluid">
        <div class="px-4 py-3">
            <div class="sub-plans-head">
                <h1><a href="{{ route('profile.index') }}" class="back-btn"></a>Change Password</h1>
            </div>
            <form action="{{ route('profile.updatePassword') }}" method="post">
                @csrf
                <div class="sub-plans-main edit-fields-main">
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <div class="input-group position-relative">
                                <label>Current Password<span class="mandatory">*</span></label>
                                <input id="current_password" name="current_password" type="text" value="" placeholder="Current Password" class="form-control website">
                                <small class="text-danger">@error('current_password'){{ $message }}@enderror</small>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <div class="input-group position-relative">
                                <label>New Password <span class="mandatory">*</span></label>
                                <input id="new_password" name="new_password" type="text" value=""  placeholder="New Password" class="form-control website">
                                <small class="text-danger">@error('new_password'){{ $message }}@enderror</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-4 mx-auto">
                            <div class="input-group position-relative">
                                <label>Confirm New Password<span class="mandatory">*</span></label>
                                <input id="new_password_confirmation" name="new_password_confirmation" type="text" value=""  placeholder="Confirm New Password" class="form-control website">
                                <small class="text-danger">@error('new_password_confirmation'){{ $message }}@enderror</small>
                            </div>
                        </div>
                    </div>  
                    <div class="d-flex justify-content-between justify-content-center mt-5">
                        <div class="already-signup">
                            
                        </div>
                        <div class="form-group proceed-btn">
                            <input type="submit" value="Save" class="btn btn-secondary">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- End Main Content -->


 
@endsection    