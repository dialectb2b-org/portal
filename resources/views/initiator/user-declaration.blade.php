@extends('layouts.app')
@section('content')
<div class="container-fluid p-0 ">
    <!-- Header Starts -->
    @include('initiator.layouts.header')
    <!-- Header Ends -->

     <!--Activation Section Starts -->
     <section class="container-fluid d-flex align-items-center login-sec md-4">
           <section class="reg-content-sec">
                        <div class="signup-fields decl-txt">
                            <h1 class="text-center mb-4">Declaration</h1>
                           <p>I hereby acknowledge and agree to the following Individual User Agreement:</p>

                            <p>By using Dialectb2b.com, I agree to comply with all the terms and conditions herein.</p>
                            
                            <p>1. I am responsible for all use of my User Account and for ensuring that all use of my User Account complies fully with the provisions of this Agreement.</p>
                            <p>2. I shall be responsible for protecting the confidentiality of my password, if any.
                            <p>3. Dialectb2b.com shall have the right at any time to change or discontinue any aspect or feature of Dialectb2b.com, including, but not limited to, content, hours of availability, and equipment needed for access or use.</p>
                            <p>4. Dialectb2b.com shall have the right at any time to change or modify the terms and conditions applicable to my use of Dialectb2b.com or any part thereof, or to impose new conditions, including, but not limited to, adding fees and charges for use. Such changes, modifications, additions, or deletions shall be effective immediately without any notice.</p>
                            <p>5. Through its Web property, Dialectb2b.com provides me with access to a variety of resources, including download areas, communication forums, and product information (collectively "Services"). The Services, including any updates, enhancements, new features, and/or the addition of any new Web properties, are subject to the Individual User Agreement.</p>
                            <p>6. The Community Guidelines, Privacy Policy, and User Agreement specified on www.dialectb2b.com, including their current version, are integral components of this Individual User Agreement.</p>
                            <p>7. I am required to subscribe to the 'Subscription and Purchase Plan' on Dialectb2b.com, entailing a prescribed fee, aimed at streamlining my business strategy and operations for enhanced opportunities. I acknowledge that I have no right to claim intended results if not achieved with Dialectb2b.com.</p>
                            <p>8. I am responsible for obtaining and maintaining all devices needed for access to and use of Dialectb2b.com, including all charges related thereto. Additionally, I am solely responsible for implementing security measures to protect my own devices for platform access.</p>
                            <p>9. I shall use Dialectb2b.com for lawful purposes only. I shall not post or transmit through Dialectb2b.com any material that violates or infringes upon the rights of others, is unlawful, threatening, abusive, defamatory, invasive of privacy or publicity rights, vulgar, obscene, profane, or otherwise objectionable. Any observed unlawful act may result in termination without notice, leading to both civil (compensation) and criminal proceedings.</p>
                            <p>10. I shall not upload, post, or otherwise make available on Dialectb2b.com any material protected by copyright, trademark, or other proprietary right without the express permission of the owner of the copyright, trademark, or other proprietary right. Such violation shall lead to the termination of this contract without notice.</p>
                            <p>11. Dialectb2b.com reserves the right to update the Individual User Agreement at any time without notice.</p>
                            
                            <p>By agreeing below, I affirm my understanding of and commitment to this Individual User Agreement.</p>
                            
                        <!--   <div class="read-declaration">-->
                        <!--   Please read the Declaration carefully and select 'Agree' to proceed.-->

                         
                        <!--</div>-->

                        </div>
                        
                        <div class="d-flex justify-content-end">
                            
                            <div class="form-group proceed-btn">
                                <input type="submit" value="Agree & Proceed" class="btn btn-secondary" onclick="window.location.href = '{{ route("activate",$user->token) }}';">
                            </div>
                            <br><br><br>
                        </div>

                    </section>
        </section>
     <!--Activation Section Starts -->
</div>
@endsection