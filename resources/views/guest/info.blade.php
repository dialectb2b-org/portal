@extends('layouts.app')
@section('content')
<div class="container-fluid p-0 login-bg">

    <header class="navbar">
        <div class="header container-fluid navbar-default d-flex align-items-center">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
            </div>
        </div>
    </header>

        
    <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <h1>Registration</h1>
                <section class="reg-content-main">
                    <div class="reg-navigation-main team-sign-nav-main">
                        <ul class="d-flex align-items-center">
                            <li class="d-flex align-items-center active-first-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">1</small>
                                Confirm Company Registration
                            </li>
                            <li class="d-flex align-items-center active">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">2</small>
                                Validate Email
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">3</small>
                                Accept Declaration
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">4</small>
                                Create Profile
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">5</small>
                                Set Password
                            </li>
                        </ul>
                    </div>

                    <section class="reg-content-sec team-sign-cont-sec">
                        <div class="signup-fields">
                          
                            <div class="row d-flex justify-content-center">
                               
                                <div class="col-md-6">
                                <form id="info-form" action="{{ route('member.checkInfo') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 text-center pb-4"><h3>Congratulations! Your organization is registered. Join using your business email ({{ '@'.$company->domain }}). For non-business emails, check our FAQ.</h3></div>

                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class=" d-flex align-items-center justify-content-center pb-4">
                                                    <img src="{{ asset($company->logo) }}" width="100px">
                                                </div>
                                                <div class="company-prof-name">
                                                    <span>{{ $company->name }}</span><br>
                                                    <a href="#">
                                                        @if (strpos($company->domain, 'www.') !== 0)
                                                            www.{{ $company->domain }}
                                                        @else
                                                            {{ $company->domain }}
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group position-relative">
                                                <label>Name<span class="mandatory">*</span></label>
                                                <input type="text" placeholder="" class="form-control" name="name">
                                                <div class="invalid-msg2">  </div>
                                            </div>
        
                                            <div class="form-group position-relative">
                                                <label>Email<span class="mandatory">*</span></label>
                                                <input type="text" placeholder="" class="form-control" name="email">
                                                <div class="invalid-msg2">  </div>
                                                
                                            </div>
                                            <div class="form-group proceed-btn d-flex justify-content-end">
                                                <input type="submit" value="Verify" class="btn btn-secondary">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                
                        
                              
                        
                            </div>
                        </div>

                        <div class="d-flex justify-content-between justify-content-center">
                            <div class="already-signup">
                                Already registered? <a href="{{ url('/') }}">Click here</a> to login
                            </div>

                        </div>
                    </section>

                </section>
            </div>
        </section>
    </div>
</div>        
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        $('.loader').hide();
        $('#info-form').submit(function(e) {
            e.preventDefault(); 
            $('.loader').show();
            $('#submit').prop('disabled',true);
            $('.invalid-msg2').hide();
            var formData = $(this).serialize(); 
            var action = $(this).attr('action');
            axios.post(action, formData)
                .then((response) => { 
                    // Handle success response
                    if(response.data.status === true){
                        localStorage.setItem('data', JSON.stringify(response.data.user));
                        window.location.href = "{{ route('member.verify') }}";
                    }
                })
                .catch((error) => {
                    // Handle error response
                    $('#submit').prop('disabled',false);
                    var firstErrorField = null;
                    if(error.response.data.type == 'nocompany'){
                        Swal.fire({
                            icon: 'warning',
                            title: "Company Not Found!",
                            text: "Your Company is not registered with us",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: "Register Now",
                            cancelButtonText: "Do It Later!",   
                        }).then(function (willDelete) {
                            if (willDelete.isConfirmed === true) {
                                window.location.href = "{{ route('sign-up') }}";
                            }
                            else{
                                window.location.href = "{{ url('/') }}";
                            }
                        });
                    }
                    if(error.response.data.type == 'nodomain'){
                        Swal.fire({
                            icon: 'warning',
                            text: "Contact your administartor to update website address from profile to proceed further.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: "Ok",  
                        }).then(function (willDelete) {
                            window.location.href = "{{ url('/') }}";
                        });
                    }
                    if(error.response.data.type == 'nocompanydomain'){
                        Swal.fire({
                            icon: 'warning',
                            text: "Only business email users acn directly signup as guest. Contact your procurement user to add you as a guest.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: "Ok",  
                        }).then(function (willDelete) {
                            window.location.href = "{{ url('/') }}";
                        });
                    }
                    if (error.response.status == 422) {
                        $.each(error.response.data.errors, function(field, errors) {
                            var input = $('input[name="' + field + '"]');
                            input.addClass('red-border');
                            var feedback = input.siblings('.invalid-msg2');
                            feedback.text(errors[0]).show();

                            // If it's the first error field, store it and focus on it
                            if (firstErrorField === null) {
                                firstErrorField = input;
                                input.focus();
                            }
                        });
                    }
                    $('.loader').hide();
                });
        });
    });
</script>
@endpush
@endsection