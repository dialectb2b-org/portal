<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Dialect</title>
</head>

<body>
    <div style="margin:0;padding:0">
        <table
            style="border-collapse:collapse;table-layout:fixed;margin:0 auto;border-spacing:0;padding:0;height:100%!important;width:100%!important;font-weight:normal;color:#3e4152;font-family:'roboto',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4"
            height="100%" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td style="background:#ffffff;padding:16px 0">
                        <table style="max-width:650px;margin:auto;border-spacing:0;padding:4px;overflow:hidden"
                            align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td style="border-collapse:collapse">
                                        <table style="margin:auto;border-spacing:0;background:white;overflow:hidden"
                                            align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="border-collapse:collapse">
                                                        <table style="border-spacing:0;border-collapse:collapse"
                                                            bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0"
                                                            width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="border-collapse:collapse;padding:0;"
                                                                        align="left" valign="middle">
                                                                        <table
                                                                            style="border-spacing:0;border-collapse:collapse"
                                                                            bgcolor="#ffffff" border="0" cellpadding="0"
                                                                            cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="padding:0;text-align:center;border-collapse:collapse; position:relative;"
                                                                                        width="650" align="center"
                                                                                        valign="middle">
                                                                                        <h1 style="position: absolute;left: 24px;top: 15px;font-size: 24px; color:#071d49;">{{ $details['subject'] ?? '' }}</h1>
                                                                                        <img src="{{ asset('assets/images/mail/emailer-header.png') }}">
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td
                                                        style="border-collapse:collapse;padding: 62px 32px;background:#ffffff;font-family:'roboto',Arial,Helvetica,sans-serif">
                                                        {!! $details['salutation'] !!}
                                                        {!! $details['introduction'] !!}
                                                        {!! $details['body'] !!}
                                                        @if($details['otp'] != null)
                                                        <span style="padding:18px 0;margin:0;font-size: 40px;font-weight: bold;color:#EC2531;text-align: center;    display: block;">{{ $details['otp'] }}</span>
                                                        @endif
                                                        @if($details['link'] != null)
                                                            <a href="{{ $details['link'] }}" target="_blank" class="v-button v-size-width" style="box-sizing: border-box;display: inline-block;font-family:'Open Sans',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #ff5770; border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px; width:30%; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
                                                            <span style="display:block;padding:10px 20px;line-height:120%;"><span style="font-size: 14px; line-height: 16.8px;">{{ $details['link_text'] }}</span></span>
                                                            </a>
                                                        @endif
                                                        {!! $details['closing'] !!}
                                                        {!! $details['closing_salutation'] !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-collapse:collapse;padding:20px 32px 30px 32px; background: #F8F7FB;font-family:'roboto',Arial,Helvetica,sans-serif;font-size:12px">
                                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                            width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="50%" style="font-size:13px;border-collapse:collapse;font-weight:normal;padding-top:10px;color:#83889E">
                                                                      

                                                                        <div style="width: 70%; float:left;">
                                                                            {{-- Dialectb2b.com --}} <br> 
                                                                       <!-- 999 Main street, Suite 101, Redwood City, CA,
                                                                        94063 USA -->
                                                                        <br>

                                                                        <a href="{{ url('/') }}"
                                                                        style="color:#1654a1;font-size:13px;padding:12px 4px 12px 0px;height:24px;display:inline-block;text-transform:capitalize;outline:0;outline:none;border:0;border:none">
                                                                        Contact Us </a>
                                                                        |
                                                                    <a href="{{ url('/') }}"
                                                                        style="color:#1654a1;font-size:13px;padding:12px 4px 12px 4px;height:24px;display:inline-block;text-transform:capitalize;outline:0;outline:none;border:0;border:none">
                                                                        dialectb2b.com </a>

                                                                        |

                                                                        <a href="#"
                                                                        style="color:#1654a1;font-size:13px;padding:12px 4px 12px 4px;height:24px;display:inline-block;text-transform:capitalize;outline:0;outline:none;border:0;border:none">
                                                                        Unsubscribe </a>

                                                                        <br>

                                                                        <a href="https://www.facebook.com/p/Dialectb2bcom-61558781600159/" style="text-decoration: none;">
                                                                            <img src="{{ asset('assets/images/mail/fb-ico.png') }}">
                                                                        </a>
                                                                        <a href="https://www.instagram.com/dialectb2b_com?igsh=amVxbHFiZ3Z0emQx" style="text-decoration: none;">
                                                                            <img src="{{ asset('assets/images/mail/insta-ico.png') }}">
                                                                        </a>
                                                                        <a href="https://twitter.com/dialectb2b_com" style="text-decoration: none;">
                                                                            <img src="{{ asset('assets/images/mail/x-ico.png') }}">
                                                                        </a>
                                                                        <a href="https://www.linkedin.com/company/dialectb2b-com" style="text-decoration: none;">
                                                                            <img src="{{ asset('assets/images/mail/linkdin-ico.png') }}">
                                                                        </a>
                                                                        <a href="https://www.youtube.com/@dialectb2b_com" style="text-decoration: none;">
                                                                            <img src="{{ asset('assets/images/mail/youtube-ico.png') }}">
                                                                        </a>
                                                                        </div>

                                                                        <div style="width: 30%; float:right; text-align:right; padding-top: 35px;">
                                                                            <a href="{{ url('/') }}"
                                                                            style="font-size:0;border:0;outline:0;border:none;outline:none;text-decoration:none;margin-right:4px">
                                                                            <img src="{{ asset('assets/images/mail/logo.png') }}" height="auto" width="175">
                                                                        </a>
                                                                        </div>
                                                                        <div style=" clear:both;"></div>
                                                                    </td>

                                                                 
                                                                    
                                                                </tr>
                                                               
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>