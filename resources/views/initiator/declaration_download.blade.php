<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #000;
  text-align: left;
  padding: 8px;
}

</style>
</head>
<body style="padding: 5px;">
<div>
    <img src="https://portal.simbillsoft.in/assets/images/logo-signup.png" />
</div>
<div>
    <h2 style="text-align:center;">Declaration</h2>
    <p style="font-size:15px;">We, <strong>{{ $company['name'] }} </strong> ,   herein referred to as "the Organization," hereby declare that we operate
within the  <strong>{{ $company['country']['name'] ?? '' }}</strong>   under Commercial Registration Certificate No. <strong>{{ $company['document']['doc_number'] ?? '' }}</strong>. The
Organization acknowledges and agrees that the terms and conditions of Dialectb2b.com shall be binding
upon all users associated with our organization while utilizing Dialectb2b.com. The Organization hereby
affirms its commitment to ensuring that all users under its jurisdiction comply fully with the terms and
conditions set forth by Dialectb2b.com. By using Dialectb2b.com, the Organization agrees to comply with all
the Terms and Conditions herein</p>
<ul style="font-size:15px;">
    <li>The Organization is responsible for the entire use of the User's Account (under any screen name or
password) and ensuring that the User's Account fully complies with the provisions of this Declaration.</li>
    <li>The User shall be responsible for protecting the confidentiality of the User's password(s).</li>
    <li>Dialectb2b.com shall have the right to change or discontinue any aspect or feature of Dialectb2b.com at
any time, including, but not limited to, content, hours of availability, and equipment needed for access or
usage.</li>
    <li>Dialectb2b.com shall have the right to change or modify the terms and conditions applicable to the
User's use of Dialectb2b.com at any time or any part thereof, or to impose new conditions, including, but
not limited to, adding fees and charges for usage. Such changes, modifications, additions, or deletions
shall be effective immediately without any notice to Organization.</li>
    <li>Through its Web property, Dialectb2b.com provides Users with access to a variety of resources, including
download areas, communication forums, and product information (collectively "Services"). The Services,
including any updates, enhancements, new features, and/or the addition of any new Web properties, are
subject to the Terms and Conditions</li>
    <li>The Community Guidelines, Privacy Policy, and User Agreement specified on  www.Dialectb2b.com,
including   their   current   version,   are   integral   components   of   these   Terms   and   Conditions   of   this
Declaration.</li>
    <li>The user is required to subscribe to the 'Subscription and Purchase Plan' on Dialectb2b.com, entailing a
prescribed fee, aimed at streamlining their business strategy and operations for enhanced opportunities.
The user acknowledges that they have no right to claim intended results if not achieved with
Dialectb2b.com.</li>
    <li>Users are responsible for obtaining and maintaining all devices needed for access and to use
Dialectb2b.com, including all charges related thereto. Additionally, users are solely responsible for
implementing security measures to protect their own devices for platform access.</li>
    <li>The User shall use Dialectb2b.com for lawful purposes only. The User shall not post or transmit through
Dialectb2b.com any material that violates or infringes upon the rights of others, is unlawful, threatening,
abusive, defamatory, invasive of privacy or publicity rights, vulgar, obscene, profane, or otherwise
objectionable. Any observed unlawful act may result in termination without any notice, leading to both
civil (compensation) and criminal proceedings.</li>
    <li>The User shall not upload, post, or otherwise make available on Dialectb2b.com any material protected
by copyright, trademark, or other proprietary right without the express permission of the owner of the
copyright, trademark, or other proprietary right. Such violations shall lead to the termination of this
contract without any notice</li>
    <li>Dialectb2b.com reserves the right to update the Terms and Conditions at any time without any notice.</li>
</ul>
</div>
<div>

    <p style="text-align: left;">Authorised Signatory:</p>
    <p style="text-align: left;">Name :</p>
    <p style="text-align: left;">Designation :</p>
    <p style="text-align: left;">Date: <strong>{{ date('d-m-Y') }}</strong></p>
    <p style="text-align: right;">Company Seal</p>
</div>
</body>
</html>

