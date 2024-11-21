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
                            <li class="d-flex align-items-center active-noradius">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">2</small>
                                Validate Email
                            </li>
                            <li class="d-flex align-items-center active">
                                <small class="reg-nav-count-active d-flex align-items-center justify-content-center">3</small>
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
                    
                    <section class="reg-content-sec">
                        <div style="line-height: 1.8; color: #333333; font-size: 16px; font-family: 'Arial', sans-serif;">
                            <h1 class="text-center mb-4">Declaration</h1>
                            <p style="margin-bottom: 12px;">I, ({{ $user['name'] ?? '' }}), hereby acknowledge and agree to the following Individual User Agreement:</p>
            
                            <ul style="padding-left: 20px; margin-bottom: 20px;">
                                <li style="margin-bottom: 8px;"> I am responsible for all use of my User Account and for ensuring that all use of my User Account complies fully with the provisions of this Agreement.</li>
                                <li style="margin-bottom: 8px;"> I shall be responsible for protecting the confidentiality of my password, if any.</li>
                                <li style="margin-bottom: 8px;"> Dialectb2b.com shall have the right at any time to change or discontinue any aspect or feature of Dialectb2b.com, including, but not limited to, content, hours of availability, and equipment needed for access or use.</li>
                                <li style="margin-bottom: 8px;"> Dialectb2b.com shall have the right at any time to change or modify the terms and conditions applicable to my use of Dialectb2b.com or any part thereof, or to impose new conditions, including, but not limited to, adding fees and charges for use. Such changes, modifications, additions, or deletions shall be effective immediately without any notice.</li>
                                <li style="margin-bottom: 8px;"> Through its Web property, Dialectb2b.com provides me with access to a variety of resources, including download areas, communication forums, and product information (collectively "Services"). The Services, including any updates, enhancements, new features, and/or the addition of any new Web properties, are subject to the Individual User Agreement.</li>
                                <li style="margin-bottom: 8px;"> The Community Guidelines, Privacy Policy, and User Agreement specified on www.dialectb2b.com, including their current version, are integral components of this Individual User Agreement.</li>
                                <li style="margin-bottom: 8px;"> I am required to subscribe to the 'Subscription and Purchase Plan' on Dialectb2b.com, entailing a prescribed fee, aimed at streamlining my business strategy and operations for enhanced opportunities. I acknowledge that I have no right to claim intended results if not achieved with Dialectb2b.com.</li>
                                <li style="margin-bottom: 8px;"> I am responsible for obtaining and maintaining all devices needed for access to and use of Dialectb2b.com, including all charges related thereto. Additionally, I am solely responsible for implementing security measures to protect my own devices for platform access.</li>
                                <li style="margin-bottom: 8px;"> I shall use Dialectb2b.com for lawful purposes only. I shall not post or transmit through Dialectb2b.com any material that violates or infringes upon the rights of others, is unlawful, threatening, abusive, defamatory, invasive of privacy or publicity rights, vulgar, obscene, profane, or otherwise objectionable. Any observed unlawful act may result in termination without notice, leading to both civil (compensation) and criminal proceedings.</li>
                                <li style="margin-bottom: 8px;"> I shall not upload, post, or otherwise make available on Dialectb2b.com any material protected by copyright, trademark, or other proprietary right without the express permission of the owner of the copyright, trademark, or other proprietary right. Such violation shall lead to the termination of this contract without notice.</li>
                                <li> Dialectb2b.com reserves the right to update the Individual User Agreement at any time without notice.</li>
                            </ul>
            
                            <p style="margin-bottom: 20px;">By agreeing below, I affirm my understanding of and commitment to this Individual User Agreement.</p>
                        </div>

                        <div class="d-flex justify-content-end">
                            
                            <div class="form-group proceed-btn">
                                <input type="submit" value="Agree & Proceed" class="btn btn-secondary" onclick="window.location.href = '{{ route("team.onboard.createProfile") }}';">
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
                        window.location.href = "{{ route('team.onboard.verify') }}";
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