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
                            <li class="d-flex align-items-center active-first">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">1</small>
                                Company Information
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">2</small>
                                Declaration
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">3</small>
                                Profile Creation
                            </li>
                            <li class="d-flex align-items-center">
                                <small class="reg-nav-count d-flex align-items-center justify-content-center">4</small>
                                Password Creation
                            </li>
                        </ul>
                    </div>
                    
                    <section class="reg-content-sec team-sign-cont-sec">
                        <div class="signup-fields">
                            <form id="sign-up-form" action="{{ route('member.signUpCheck') }}" method="post">
                                @csrf
                                <div class="row d-flex justify-content-center">
                                    <div class="col-md-6">
                                        <p>Welcome to Dialectb2b.com!</p>
                                         <small class="first-cont">Enter your organization's Commercial Registration Number (CR) and select country.<br>
                                                                    Click 'Verify' to confirm registration.<br>
                                                                    Proceed with Team user registration if registered.<br>
                                                                    If not registered, sign up through your organization representative.<br>
                                                                    Need help? Contact us via chat support.</small>
                                        <div class="form-group position-relative">
                                            <label>Company ID<span class="mandatory">*</span></label>
                                            <input type="text" placeholder="Company ID" class="form-control" name="company_id">
                                            <div class="invalid-msg2"></div>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label>Country <span class="mandatory">*</span></label>
                                            <div class="select-drop3">
                                                <select id="standard-select" name="country_id" class="country">
                                                    @foreach($countries as $key => $country)
                                                    <option value="{{ $country->id }}" data-phone_code="{{ $country->phonecode }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-msg2"> </div>
                                            </div>         
                                        </div>
                                        <div class="form-group proceed-btn d-flex justify-content-end">
                                            <input id="submit" type="submit" value="Proceed" class="btn btn-secondary mt-3">
                                        </div>
                                    </div>
                                </div>
                            </form>
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
<script>
    $(document).ready(function() {
        $('.loader').hide();
        var code = $('.country  option:selected').data('phone_code');
        $('#country_code').val(code);
        $('#sign-up-form').submit(function(e) {
            e.preventDefault(); 
            $('#submit').prop('disabled',true);
            $('.invalid-msg2').hide();
            var formData = $(this).serialize(); 
            var action = $(this).attr('action');
            $.ajax({
                url: action,
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    if(data.status === true){
                        window.location.href = "{{ route('member.signUpInfo') }}";
                    }
                    //console.log(data);
                },
                error: function(xhr, status, error) {
                    $('#submit').prop('disabled',false);
                    if(xhr.responseJSON.status === false){
                        Swal.fire({
                            icon: 'warning',
                            title: xhr.responseJSON.message,
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
                    var response = JSON.parse(xhr.responseText);
                    var firstErrorField = null;
                    if (response.errors) {
                        $('input').removeClass('red-border');
                        $.each(response.errors, function(field, errors) {
                            if(field === 'mobile'){
                                var minput = $('input[name="' + field + '"]');
                                var mfeedback = minput.parent().next('.invalid-msg2');
                                mfeedback.html(errors[0]).show();
                            }
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
                },
                complete: function(data) {
                    $('#submit').prop('disabled',false); 
                    $('.loader').hide();
                }
            });
        });
    });
</script>
@endpush
@endsection