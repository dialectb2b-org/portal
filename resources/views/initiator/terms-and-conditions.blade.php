@extends('layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('initiator.layouts.header')
    <!-- Header Ends -->
    <embed src="{{ asset('UserAgreementCompany.pdf') }}" type="application/pdf" class="license-preview" style="width: 98vw; height: 90vh; border: none;margin-top: 0 !important;">
    {{-- <div class="container mt-5">
        <h1>User Agreement</h1>
        
        <h2>Introduction</h2>
        <p>Welcome to Dialectb2b.com! We prioritize your privacy and are committed to
            ensuring a clear understanding of how we handle your information and that of
            your organization when you utilize our services. This User Agreement
            delineates crucial terms regarding data usage and privacy protection for both
            Organization and its individual users. By engaging with our services, including
            but not limited to signing up, joining Dialectb2b.com, or accessing our website
            (www.dialectb2b.com), you will enter into a binding legal agreement with us.
            This agreement, herein referred to as the "Declaration" or "User Agreement,"
            comprehensively addresses important facets such as the acquisition, utilization,
            and safeguarding of your information. It is imperative to thoroughly review and
            consent to these terms. In the event of disagreement with our Agreement, kindly
            refrain from engaging in actions such as "Signup as Organization" or
            "Individual," or similar, and abstain from utilizing our services. To terminate
            this Agreement, simply close your account and cease utilizing our services. We
            expressly reserve the right to periodically update this Agreement, our Privacy
            Policy, and all other pertinent policies. Noticeable modifications will be
            communicated through our services or other appropriate channels. We further
            reserve the right to update the Terms and Conditions at any time without
            providing notice. Continued utilization of our services post-notification of
            changes denotes acceptance of the updated terms effective from the date of
            notification.</p>
        
        <h3>Obligations of Users</h3>
        <p>As a user affiliated with an organization using Dialectb2b.com, you agree to uphold the following responsibilities:</p>
        
        <ol type="a">
            <li><strong>Account Usage:</strong>
                <ul>
                    <li>Use your account responsibly and adhere to the terms outlined in this agreement.</li>
                    <li>Your account is intended for professional use related to your organization's activities.</li>
                    <li>Respect the rights of other users and refrain from engaging in any behavior that violates our terms of service.</li>
                </ul>
            </li>
            <li><strong>Password Security:</strong>
                <ul>
                    <li>Maintain the confidentiality of your password and ensure it meets security standards.</li>
                    <li>Regularly update your password and avoid using the same password for multiple accounts.</li>
                    <li>Do not share your password with anyone else, including colleagues or third parties.</li>
                </ul>
            </li>
            <li><strong>Compliance:</strong>
                <ul>
                    <li>Follow all applicable laws, organizational policies, and Dialectb2b.com guidelines.</li>
                    <li>Familiarize yourself with our terms of service, privacy policy and other relevant documents.</li>
                </ul>
            </li>
        </ol>

        <h3>DOs and DON'Ts:</h3>

        <h4>DOs:</h4>

        <p><strong>Use Dialectb2b.com for Lawful Purposes:</strong></p>
        <ul>
            <li>Ensure that your activities on Dialectb2b.com comply with applicable laws and regulations.</li>
            <li>Engage in professional interactions and transactions that align with legal and ethical standards.</li>
        </ul>

        <p><strong>Respect Others:</strong></p>
        <ul>
            <li>Treat fellow users with respect and professionalism.</li>
            <li>Foster a collaborative and supportive environment conducive to productive networking and business interactions.</li>
        </ul>

        <p><strong>Protect Confidential Information:</strong></p>
        <ul>
            <li>Safeguard sensitive information shared on the platform.</li>
            <li>Exercise caution when sharing proprietary or confidential data and adhere to your Organization's data security policies.</li>
        </ul>

        <h4>DON'Ts:</h4>

        <p><strong>Engage in Unlawful Activities:</strong></p>
        <ul>
            <li>Refrain from participating in any activities that violate laws or regulations.</li>
            <li>Avoid engaging in fraudulent, deceptive, or unethical practices that undermine trust and integrity.</li>
        </ul>

        <p><strong>Harassment and Discrimination:</strong></p>
        <ul>
            <li>Do not engage in harassment, discrimination, or bullying behaviour towards others.</li>
            <li>Respect diversity and inclusivity, and refrain from actions that demean or harm individuals based on characteristics such as race, gender, or nationality.</li>
        </ul>

        <p><strong>Misuse of Platform:</strong></p>
        <ul>
            <li>Avoid misuse or abuse of Dialectb2b.com features and functionalities.</li>
            <li>Do not engage in activities that disrupt or compromise the platform's integrity, security, or performance.</li>
        </ul>

        <h4>Payment Responsibilities</h4>

        <p>If your organization opts for any paid services on Dialectb2b.com:</p>
        <p><strong>Payment Responsibilities:</strong></p>
        <ul>
            <li>Refrain from participating in any activities that violate laws or regulations.</li>
            <li>Avoid engaging in fraudulent, deceptive, or unethical practices that undermine trust and integrity.</li>
        </ul>

        <p><strong>Harassment and Discrimination:</strong></p>
        <ul>
            <li>Do not engage in harassment, discrimination, or bullying behaviour towards others.</li>
            <li>Respect diversity and inclusivity, and refrain from actions that demean or harm individuals based on characteristics such as race, gender, or nationality.</li>
        </ul>

        <p><strong>Misuse of Platform:</strong></p>
        <ul>
            <li>Avoid misuse or abuse of Dialectb2b.com features and functionalities.</li>
            <li>Do not engage in activities that disrupt or compromise the platform's integrity, security, or performance.</li>
        </ul>

    </div> --}}
@endsection