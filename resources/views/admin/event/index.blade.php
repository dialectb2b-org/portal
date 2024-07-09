@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content -->
    <div class="container-fluid d-flex justify-content-center align-items-center vh-height">

        <div class="coming-soon-main">
            <div class="row mb-3">
                <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/coming-soon-ico.svg') }}" alt="XCHANGE">
                    <h1 class="d-block">Coming Soon</h1>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">

                <div class="form-group proceed-btn">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Go to Home Page</a>
                </div>
            </div>
        </div>

    </div>
    <!-- End Main Content -->

@push('scripts')
    
@endpush
 
@endsection    