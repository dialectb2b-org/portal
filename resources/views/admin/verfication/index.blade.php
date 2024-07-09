@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->
    
        <section class="container-fluid pleft-77">
            <div class="px-4 py-3">
                <h1 class="mb-4 mt-2">Account Verification</h1>
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
        </div>
    </section>
    
    
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Instruction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       Use the company bank account for verification payments to proceed.
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
      $('#exampleModal').modal('show'); 
    });
</script>
@endpush
@endsection