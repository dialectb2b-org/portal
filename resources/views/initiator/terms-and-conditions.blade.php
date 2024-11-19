@extends('layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('initiator.layouts.header')
    <!-- Header Ends -->
    {{-- <embed src="{{ asset('UserAgreementCompany.pdf') }}" type="application/pdf" class="license-preview" style="width: 98vw; height: 90vh; border: none;margin-top: 0 !important;"> --}}

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Info" />
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
                text-indent: 0;
            }
            .terms-body{
                padding: 10px 30px;
            }

            h1 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 20pt;
                /* text-decoration: underline; */
            }

            h2 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 18pt;
                margin:10px 0 20px 0;
                text-decoration: underline;
            }

            p {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 14pt;
                margin: 0pt;
                line-height: 135% !important;
            }

            .a {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 14pt;
            }

            h4 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 14pt;
            }

            h3 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 16pt;
            }

            .s1 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 10pt;
                line-height: 135% !important;
            }

            .s2 {
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 14pt;
                line-height: 135% !important;
            }

            li {
                display: block;
            }

            #l1 {
                padding-left: 10pt;
                counter-reset: c1 1;
            }

            #l1>li>*:first-child:before {
                counter-increment: c1;
                content: counter(c1, lower-latin) ". ";
                color: black;
                font-family: "Times New Roman", serif;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                font-size: 14pt;
            }

            #l1>li:first-child>*:first-child:before {
                counter-increment: c1 0;
            }

            #l2 {
                padding-left: 0pt;
            }

            #l2>li>*:first-child:before {
                content: "· ";
                color: black;
                font-family: Verdana, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
            }

            #l3 {
                padding-left: 0pt;
            }

            #l3>li>*:first-child:before {
                content: "· ";
                color: black;
                font-family: Verdana, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
            }

            #l4 {
                padding-left: 0pt;
            }

            #l4>li>*:first-child:before {
                content: "· ";
                color: black;
                font-family: Verdana, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
            }
            span img {
                display: none;
            }

            span:has(img)::before {
                content: '';
                display: inline-block;
                width: 10px; /* Adjust size */
                height: 10px; /* Keep it square */
                background-color: black; /* Desired color */
                border-radius: 0; /* No rounding for a square */
                margin-right: 10px;
            }
        </style>
    </head>

    <body>
        <div class="terms-body">
        <span class="back-btn" style="margin-left: 20px;" onclick="window.close();"></span>
        <h1 style="padding-top: 3pt; text-indent: 0pt; text-align: center;margin-top: 30px;">
            User Agreement
        </h1>
        <h2
            style="
                padding-top: 14pt;
                padding-left: 5pt;
                text-indent: 0pt;
                text-align: left;
              ">
            Introduction
        </h2>
        <p
            style="
                padding-left: 5pt;
                text-indent: 0pt;
                line-height: 92%;
                text-align: justify;
              ">
            Welcome to Dialectb2b.com! We prioritize your
            privacy and are committed
            to ensuring a clear understanding of how we handle your information and
            that of your organization when you utilize our services. This User
            Agreement delineates crucial terms regarding data usage and privacy
            protection for both Organization and its individual users. By engaging
            with our services, including but not limited to signing up, joining
            Dialectb2b.com, or accessing our website (<a href="http://www.dialectb2b.com/" class="a" target="_blank">www.dialectb2b.com</a>), you will enter into a binding legal
            agreement with
            us. This agreement, herein referred to as the &quot;Declaration&quot; or
            &quot;User Agreement,&quot; comprehensively addresses important facets
            such as the acquisition, utilization, and safeguarding of your
            information. It is imperative to thoroughly review and consent to these
            terms. In the event of disagreement with our Agreement, kindly refrain
            from engaging in actions such as &quot;Signup as Organization&quot; or
            &quot;Individual,&quot; or similar, and abstain from utilizing our
            services. To terminate this Agreement, simply close your account and cease
            utilizing our services. We expressly reserve the right to periodically
            update this Agreement, our Privacy Policy, and all other pertinent
            policies. Noticeable modifications will be communicated through our
            services or other appropriate channels. We further reserve the right to
            update the Terms and Conditions at any time without providing notice.
            Continued utilization of our services post-notification of changes denotes
            acceptance of the updated terms effective from the date of notification.
        </p>
        <h2
            style="
                padding-top: 15pt;
                padding-left: 5pt;
                text-indent: 0pt;
                text-align: justify;
              ">
            Obligations of Users
        </h2>
        <p
            style="
                padding-top: 14pt;
                padding-left: 5pt;
                text-indent: 0pt;
                line-height: 92%;
                text-align: justify;
              ">
            As a user affiliated with an organization using Dialectb2b.com, you agree
            to uphold the following responsibilities:
        </p>
        <ol id="l1">
            <li data-list-text="a.">
                <h4
                    style="
                    padding-top: 15pt;
                    padding-left: 41pt;
                    text-indent: -36pt;
                    line-height: 15pt;
                    text-align: left;
                  ">
                    Account Usage:
                </h4>
                <ul id="l2">
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Use your account responsibly and adhere to the terms outlined in
                            this agreement.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Your account is intended for professional use related to your
                            organization&#39;s activities.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Respect the rights of other users and refrain from engaging in any
                            behaviour that violates our terms of service.
                        </p>
                    </li>
                </ul>
            </li>
            <li data-list-text="b.">
                <h4
                    style="
                    padding-top: 14pt;
                    padding-left: 40pt;
                    text-indent: -35pt;
                    line-height: 15pt;
                    text-align: left;
                  ">
                    Password Security:
                </h4>
                <ul id="l3">
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Maintain the confidentiality of your password and ensure it meets
                            security standards.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Regularly update your password and avoid using the same password
                            for multiple accounts.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Do not share your password with anyone else, including colleagues
                            or third parties.
                        </p>
                    </li>
                </ul>
            </li>
            <li data-list-text="c.">
                <h4
                    style="
                    padding-top: 2pt;
                    padding-left: 40pt;
                    text-indent: -35pt;
                    line-height: 15pt;
                    text-align: left;
                  ">
                    Compliance:
                </h4>
                <ul id="l4">
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Follow all applicable laws, organizational policies, and
                            Dialectb2b.com guidelines.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Familiarize yourself with our terms of service, privacy policy and
                            other relevant documents.
                        </p>
                        <h2
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            DOs and DON&#39;Ts:
                        </h2>
                        <h3
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            DOs:
                        </h3>
                        <p
                            style="
                        padding-top: 17pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Use Dialectb2b.com for Lawful Purposes:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_001.png" /></span>
                            <span class="s2">Ensure that your activities on Dialectb2b.com comply with
                                applicable laws and regulations.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_002.png" /></span>
                            <span class="s2">Engage in professional interactions and transactions that align
                                with legal and ethical standards.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Respect Others:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 58pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_003.png" /></span>
                            <span class="s2">Treat fellow users with respect and professionalism.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_004.png" /></span>
                            <span class="s2">Foster a collaborative and supportive environment conducive to
                                productive networking and business interactions.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Protect Confidential Information:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 58pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_005.png" /></span>
                            <span class="s2">Safeguard sensitive information shared on the platform.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_006.png" /></span>
                            <span class="s2">Exercise caution when sharing proprietary or confidential data
                                and adhere to your Organization&#39;s data security
                                policies.</span>
                        </p>
                        <h3
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            DON&#39;Ts:
                        </h3>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Engage in Unlawful Activities:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_007.png" /></span>
                            <span class="s2">Refrain from participating in any activities that violate laws
                                or regulations.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_008.png" /></span>
                            <span class="s2">Avoid engaging in fraudulent, deceptive, or unethical practices
                                that undermine trust and integrity.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Harassment and Discrimination:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_009.png" /></span>
                            <span class="s2">Do not engage in harassment, discrimination, or bullying
                                behaviour towards others.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_010.png" /></span>
                            <span class="s2">Respect diversity and inclusivity, and refrain from actions
                                that demean or harm individuals based on characteristics such as
                                race, gender, or nationality.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Misuse of Platform:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_011.png" /></span>
                            <span class="s2">Avoid misuse or abuse of Dialectb2b.com features and
                                functionalities.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-top: 2pt;
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_012.png" /></span>
                            <span class="s2">Do not engage in activities that disrupt or compromise the
                                platform&#39;s integrity, security, or performance.</span>
                        </p>
                        <h2
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Payment Responsibilities
                        </h2>
                        <p
                            style="
                        padding-top: 2pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 30pt;
                        text-align: justify;
                      ">
                            If your organization opts for any paid services on Dialectb2b.com:
                            Payment Responsibilities:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 58pt;
                        text-indent: 0pt;
                        line-height: 11pt;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_013.png" /></span>
                            <span class="s2">Agree to pay all associated fees and taxes promptly. Failure to
                                do</span>
                        </p>
                        <p
                            style="
                        padding-left: 77pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            so may lead to termination of paid services.
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_014.png" /></span>
                            <span class="s2">Review pricing information and billing details before
                                purchasing any paid services.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_015.png" /></span>
                            <span class="s2">Ensure timely payment to avoid service interruptions or account
                                suspension.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Additional Costs:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_016.png" /></span>
                            <span class="s2">Be aware of potential foreign exchange fees or price variances
                                based on location.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_017.png" /></span>
                            <span class="s2">Understand any additional charges that may apply to your
                                Organization&#39;s purchases, such as currency conversion fees
                                or taxes.</span>
                        </p>
                        <h2
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Subscription Charges:
                        </h2>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            Understand that automatic billing occurs at the start of each
                            subscription period. Cancelling the subscription before the
                            renewal date prevents future charges.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Access the Subscription Management section from your
                            Organization’s Admin owner profile for further details.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: justify;
                      ">
                            Familiarize yourself with the subscription renewal process and
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            cancellation policy.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            Take proactive steps to manage your Organization&#39;s
                            subscriptions to avoid unexpected charges.
                        </p>
                        <h2
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Subscription Cancellation:
                        </h2>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Monthly Subscriptions:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_018.png" /></span>
                            <span class="s2">When cancelling a monthly subscription, all future charges
                                associated with upcoming months of your subscription will be
                                cancelled.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_019.png" /></span>
                            <span class="s2">You may notify us your intention to cancel the subscription at
                                any time, then your cancellation will be effective only at the
                                end of your current monthly billing period.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-top: 2pt;
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_020.png" /></span>
                            <span class="s2">You will not receive a refund; however, your subscription
                                access and/or delivery and associated subscriber benefits will
                                continue for the remaining current monthly billing period.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: justify;
                      ">
                            Annual Subscriptions:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_021.png" /></span>
                            <span class="s2">When cancelling an annual subscription, all future charges
                                associated with future years of your subscription will be
                                cancelled.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_022.png" /></span>
                            <span class="s2">You may notify us your intention to cancel the subscription at
                                any time, then your cancellation will be effective only at the
                                end of your current annual billing period.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_023.png" /></span>
                            <span class="s2">You will not receive a refund, prorated or otherwise, for the
                                remaining annual billing period.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_024.png" /></span>
                            <span class="s2">Your subscription access and/or delivery and associated
                                subscriber benefits will continue for the remaining current
                                annual billing period.</span>
                        </p>
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            In order to cancel your subscription:
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 58pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_025.png" /></span>
                            <span class="s2">Please Sign in to your Dialectb2b.com company admin
                                Account.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_026.png" /></span>
                            <span class="s2">Navigate to the “Subscription” tab at the top right-side
                                profile setting.</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 58pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_027.png" /></span>
                            <span class="s2">Click “Cancel your subscription”</span>
                        </p>
                        <p class="s1"
                            style="
                        padding-left: 77pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <span><img width="6" height="6" alt="image"
                                    src="f8cd1559-b41a-4f7e-8ffa-084a54008263_files/Image_028.png" /></span>
                            <span class="s2">If you are unable to sign in, you may click on “Chat Now” at
                                the bottom of the page to start a conversation with
                                Dialectb2b.com Chat Assistant.</span>
                        </p>
                        <h2
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Rights and Limits:
                        </h2>
                        <h3
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Sharing Information:
                        </h3>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Ensure compliance with your Organization&#39;s data sharing
                            policies and relevant laws when sharing the information on our
                            platform.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Exercise caution when sharing sensitive information and adhere to
                            data protection regulations.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Respect the privacy rights of other users and obtain consent
                            before
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            sharing personal or confidential information.
                        </p>
                        <h3
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Your Content:
                        </h3>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            By sharing content on Dialectb2b.com, you grant us the right to
                            use, modify, and distribute it in accordance with our Privacy
                            Policy.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Review our Privacy Policy to understand how your content may be
                            used and shared on our platform.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Exercise discretion when sharing the content and ensure that you
                            have
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            necessary rights and permissions to do so.
                        </p>
                        <h3
                            style="
                        padding-top: 3pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Changes and Discontinuation:
                        </h3>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            We reserve the right to modify or discontinue services with
                            reasonable notice, as allowed by law.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Stay informed about the changes to our services and terms of
                            service by reviewing updates regularly.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Contact Dialectb2b.com support, if you may have any questions or
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            concerns about the changes to our services.
                        </p>
                        <h3
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Ending the Contract:
                        </h3>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 12pt;
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 16pt;
                        text-align: left;
                      ">
                            Either party can terminate this Contract at any time.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Certain provisions, including payment obligations and feedback
                            usage, will survive termination.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Understand the implications of terminating the contract, including
                            the loss of access to services and the continuation of certain
                            obligations.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Follow the proper procedures for closing your account and
                            terminating
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            the contract to avoid any misunderstandings or disputes.
                        </p>
                        <h2
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Governing Law and Disputes:
                        </h2>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            This Contract is governed by the laws of State of Qatar. Disputes
                            shall be resolved through discussions or by the Courts of Qatar.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Familiarize yourself with the applicable laws and dispute
                            resolution mechanisms in the State of Qatar.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Attempt to resolve any disputes amicably before resorting to legal
                            action,
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            in accordance with the terms of this agreement.
                        </p>
                        <h2
                            style="
                        padding-top: 14pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Contact Information:
                        </h2>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 13pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            For inquiries or legal matters, please contact us at the address
                            provided on the Dialectb2b.com Website.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Use the designated contact channels for inquiries, feedback, or
                            legal notices to ensure timely and appropriate responses.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 14pt;
                        text-align: left;
                      ">
                            Provide accurate and complete information while contacting
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: left;
                      ">
                            Dialectb2b.com support to facilitate efficient communication and
                            resolution.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            By agreeing below, you affirm your understanding of and commitment
                            to this User Agreement.
                        </p>
                        <h2
                            style="
                        padding-top: 15pt;
                        padding-left: 5pt;
                        text-indent: 0pt;
                        text-align: left;
                      ">
                            Declaration:
                        </h2>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-top: 5pt;
                        padding-left: 41pt;
                        text-indent: -18pt;
                        line-height: 90%;
                        text-align: justify;
                      ">
                            The Organization is responsible for the entire use of the
                            User&#39;s Account (under any screen name or password) and
                            ensuring that the User&#39;s Account fully complies with the
                            provisions of this Declaration.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            The User shall be responsible for protecting the confidentiality
                            of the
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                      ">
                            User&#39;s password(s).
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 18pt;
                        text-align: left;
                      ">
                            Dialectb2b.com shall have the right to change or discontinue any
                            aspect
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            or feature of Dialectb2b.com at any time, including, but not
                            limited to, content, hours of availability, and equipment needed
                            for access or usage.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Dialectb2b.com shall have the right to change or modify the terms
                            and
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            conditions applicable to the User&#39;s use of Dialectb2b.com at
                            any time or any part thereof, or to impose new conditions,
                            including, but not limited to, adding fees and charges for usage.
                            Such changes, modifications, additions, or deletions shall be
                            effective immediately without any notice.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Through its Web property, Dialectb2b.com provides Users with
                            access to
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            a variety of resources, including download areas, communication
                            forums, and product information (collectively
                            &quot;Services&quot;). The Services, including any updates,
                            enhancements, new features, and/or the addition of any new Web
                            properties, are subject to the Terms and Conditions.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            The Community Guidelines, Privacy Policy, and User Agreement
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            <a href="http://www.Dialectb2b.com/" class="a" target="_blank">specified on
                            </a>www.Dialectb2b.com, including their current version, are integral
                            components of these Terms and Conditions of this Declaration.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            The user is required to subscribe to the &#39;Subscription and
                            Purchase Plan&#39;
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            on Dialectb2b.com, entailing a prescribed fee, aimed at
                            streamlining their business strategy and operations for enhanced
                            opportunities. The user acknowledges that they have no right to
                            claim intended results if not achieved with Dialectb2b.com.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Users are responsible for obtaining and maintaining all devices
                            needed
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            for access and to use Dialectb2b.com, including all charges
                            related thereto. Additionally, users are solely responsible for
                            implementing security measures to protect their own devices for
                            platform access.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            The User shall use Dialectb2b.com for lawful purposes only. The
                            User
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            shall not post or transmit through Dialectb2b.com any material
                            that violates or infringes upon the rights of others, is unlawful,
                            threatening, abusive, defamatory, invasive of privacy or publicity
                            rights, vulgar, obscene, profane, or otherwise objectionable. Any
                            observed unlawful act may result in termination without any
                            notice, leading to both civil (compensation) and criminal
                            proceedings.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            The User shall not upload, post, or otherwise make available on
                        </p>
                        <p
                            style="
                        padding-left: 41pt;
                        text-indent: 0pt;
                        line-height: 92%;
                        text-align: justify;
                      ">
                            Dialectb2b.com any material protected by copyright, trademark, or
                            other proprietary right without the express permission of the
                            owner of the copyright, trademark, or other proprietary right.
                            Such violations shall lead to the termination of this contract
                            without any notice.
                        </p>
                    </li>
                    <li data-list-text="·">
                        <p
                            style="
                        padding-left: 40pt;
                        text-indent: -17pt;
                        line-height: 15pt;
                        text-align: left;
                      ">
                            Dialectb2b.com reserves the right to update the Terms and
                            Conditions at
                        </p>
                    </li>
                </ul>
            </li>
        </ol>
        <p
            style="
                padding-left: 41pt;
                text-indent: 0pt;
                line-height: 15pt;
                text-align: justify;
              ">
            any time without any notice.
        </p>
        <p
            style="
                padding-top: 2pt;
                padding-left: 5pt;
                text-indent: 0pt;
                line-height: 92%;
                text-align: left;
              ">
            By Agreeing, you affirm your understanding of and commitment to this User
            Agreement.
        </p>
    </div>
    </body>
@endsection
