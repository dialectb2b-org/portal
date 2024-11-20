@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <!-- Header Starts -->
    @include('initiator.layouts.header')
    <!-- Header Ends -->

    <!-- Activation Section Starts -->
    <section class="container-fluid d-flex align-items-center justify-content-center login-sec py-4">
        <section class="reg-content-sec" style="width: 100%; padding: 20px;">
            <div class="card signup-fields decl-txt" style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; background: #ffffff;">
                <!-- Header -->
                <div style="height: 72px; width: 100%; background: #20285B; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin-bottom: 20px;">
                    <h1 style="font-size: 28px; margin: 0; font-weight: bold; font-family: Arial, sans-serif; color: #ffffff; text-transform: uppercase;">
                        Declaration
                    </h1>
                </div>
    
                <!-- Declaration Content -->
                <div style="line-height: 1.8; color: #333333; font-size: 16px; font-family: 'Arial', sans-serif;">
                    <p style="margin-bottom: 12px;">I hereby acknowledge and agree to the following Individual User Agreement:</p>
    
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
    
                <!-- Action Button -->
                <div class="d-flex justify-content-end">
                    <input type="submit" value="Agree & Proceed" class="btn btn-secondary" onclick="window.location.href = '{{ route("activate", $user->token) }}';">
                </div>
            </div>
        </section>
    </section>
    <!-- Activation Section Ends -->
</div>
@endsection