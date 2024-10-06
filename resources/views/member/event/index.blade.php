@extends('member.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('member.layouts.header')
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
                    <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">Go to Home Page</a>
                </div>
            </div>
        </div>

    </div>
    <!-- End Main Content -->

@push('scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush
 
@endsection    