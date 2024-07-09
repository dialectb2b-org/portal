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
                                    <table
                                        style="max-width:650px;margin:auto;border-spacing:0;padding:4px;overflow:hidden"
                                        align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="border-collapse:collapse">
                                                    <table
                                                        style="margin:auto;border-spacing:0;background:white;overflow:hidden"
                                                        align="center" border="0" cellpadding="0" cellspacing="0"
                                                        width="100%">
                                                        <tbody>
                                                            
                                                            <tr>
                                                                <td
                                                                    style="border-collapse:collapse;padding: 62px 32px;background:#ffffff;font-family:'roboto',Arial,Helvetica,sans-serif">
                                                                    <p style="text-align: left;">{{ $details['name'] ?? 'User' }},
                                                                    </p>
                                                                    <p style="text-align: center;">{!! $details['body'] !!}
                                                                    </p>
                                                                    @if(isset($details['link']))
                                                                    <a href="{{ $details['link'] }}" target="_blank" class="v-button v-size-width" style="box-sizing: border-box;display: inline-block;font-family:'Open Sans',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #ff5770; border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px; width:30%; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
                                                                      <span style="display:block;padding:10px 20px;line-height:120%;"><span style="font-size: 14px; line-height: 16.8px;">Continue to your account</span></span>
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="border-collapse:collapse;padding:16px 32px; background: #F8F7FB;font-family:'roboto',Arial,Helvetica,sans-serif;font-size:12px">
                                                                    <table align="center" border="0" cellpadding="0"
                                                                        cellspacing="0" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="left" valign="middle"
                                                                                    style="border-collapse:collapse;font-weight:normal">
                                                                                    <a href="#" style="color:#673ab7;font-size:12px;padding:4px;height:24px;display:inline-block;text-transform:capitalize;outline:0;outline:none;border:0;border:none">
                                                                                        Privacy </a> 
                                                                                        <a href="#"
                                                                                        style="color:#673ab7;font-size:12px;padding:4px;height:24px;display:inline-block;text-transform:capitalize;outline:0;outline:none;border:0;border:none" >
                                                                                        Contact </a></td>
                                                                                <td width="16" align="left" valign="middle"
                                                                                    style="border-collapse:collapse">&nbsp;
                                                                                </td>
                                                                                <td align="right" valign="middle"
                                                                                    style="border-collapse:collapse">
                                                                                    <table border="0" cellspacing="0"
                                                                                        cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="border-collapse:collapse">
                                                                                                    <a href="#"
                                                                                                        style="font-size:0;border:0;outline:0;border:none;outline:none;text-decoration:none;margin-right:4px">
                                                                                                        <img src="{{ asset('assets/images/mail/logo.svg') }}">
                                                                                                    </a> 
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-collapse:collapse;font-weight:normal;padding-top:16px;font-style:italic;color:#7e818c"
                                                                                    colspan="3"> DialectB2B LLC <br>
                                                                                   <!-- 999 Main street, Suite 101, Redwood City, CA, 94063 USA -->
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