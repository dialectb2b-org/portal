
<html>
	<head>
		<meta charset="utf-8">
		<title>DialectB2b Invoice</title>
	</head>
	<body>
	    <style>
	        .container-fluid {
                width: 100%;
                padding-right: var(--bs-gutter-x,.75rem);
                padding-left: var(--bs-gutter-x,.75rem);
                margin-right: auto;
                margin-left: auto;
            }
	        .reg-bg {
                background: #F9F9F9;
                height: calc(100vh - 96px);
            }
            .registration {
                padding: 30px 0;
            }
            .reg-content-main {
                background: #ffffff;
                border-radius: 12px;
                margin-top: 20px;
                padding: 0;
                border-left: 1px solid #E9E9E9;
                border-right: 1px solid #E9E9E9;
                border-bottom: 1px solid #E9E9E9;
                -webkit-box-shadow: 0px 3px 6px 0px rgba(230, 233, 255, 1);
                -moz-box-shadow: 0px 3px 6px 0px rgba(230, 233, 255, 1);
                box-shadow: 0px 3px 6px 0px rgba(230, 233, 255, 1);
            }
            .invoice-main-sec {
                padding: 45px 40px;
            }
            .d-flex {
                display: flex!important;
            }
            .justify-content-between {
                justify-content: space-between!important;
            }
            .invoice-detail{
                margin: 0;
                padding: 0;
                font-family: 'Roboto', sans-serif;
                font-weight: 400;
                font-size: 100%;
                color: #071D49;
                /* vertical-align: middle; */
            }
            .invoice-table-header {
                background: #20285B;
                height: 48px;
                position: relative;
                font-size: 14px;
                text-align: right;
                color: #fff;
                padding-right: 20px;
            }
            .mt-5 {
                margin-top: 3rem!important;
            }
            .align-items-center {
                align-items: center!important;
            }
            .justify-content-end {
                justify-content: flex-end!important;
            }
            .item-description {
                background: linear-gradient(70deg, #EC2531 30%, rgba(0, 0, 0, 0) 30%);
                position: absolute;
                height: 61px;
                width: 100%;
                top: -6px;
                color: #fff;
                font-size: 14px;
                left: 0;
            }
            .item-description span {
                color: #fff;
                padding-left: 15px;
                padding-right: 35px;
            }
            .table {
                border-color: #909BA3;
            }
            .table>tbody {
                vertical-align: inherit;
            }
            
            .invoice-main-sec label{
              font-size: 14px;
              color: #5F6F87;
              line-height: 20px;
              margin-top: 15px;
            }
            .invoice-detail h1{
              font-size: 38px;
              font-weight: normal;
              padding-bottom: 20px;
              text-align: left;
            }
            .invoice-detail span{
              font-size: 14px;
              font-weight: bold;
              display: block;
              color: #20285B;
            
            }
            .invoice-table-header{
              background: #20285B;
              height: 48px;
              position: relative;
              font-size: 14px;
              text-align: right;
              color: #fff;
              padding-right: 20px;
            }
            .item-description{
              background: linear-gradient(70deg, #EC2531 30%, rgba(0, 0, 0, 0) 30%);
              position: absolute;
              height: 61px;
              width: 100%;
              top: -6px;
              color: #fff;
              font-size: 14px;
              left: 0;
            }
            .item-description span{
              color: #fff;
              padding-left: 15px;
              padding-right: 35px;
            }
            table.invoice{
              border-bottom: 1px solid #909BA3;
              min-height: 200px;
              margin-bottom: 0;
            }
            table.invoice tr{
              border: none !important;
            }
            table.invoice tr.first-td td{
              padding-top: 25px;
            }
            table.invoice td.amount{
              font-size: 17px;
              font-weight: bold;
              text-align: right;
            }
            table.invoice td{
              border-right: 1px solid #909BA3;
              border-bottom: none;
              padding-top: 12px;
              padding-bottom: 12px;
              height: 25px;
            }
            table.invoice td:first-child{
              width: 25px;
            }
            table.invoice td:last-child{
              border-right: none;
              width: 160px;
            }
            
            
            table.invoice-total td{
              padding-top: 15px;
              padding-bottom: 15px;
            }
            table.invoice-total td.amount{
              font-size: 17px;
              font-weight: bold;
              text-align: right;
            }
            
            table.invoice-total td.total-txt{
              font-size: 17px;
              font-weight: bold;
              text-align: left;
              padding-left: 50px;
            }
            table.invoice-total tr.tax-td td{
              border-bottom: none !important;
            }
            .total-main-amount{
              background: linear-gradient(-70deg, #EC2531 84%, rgba(0, 0, 0, 0) 84%);
              position: absolute;
              height: 61px;
              width: 200px;
              top: -6px;
              color: #fff;
              font-size: 14px;
              right:0;
              text-align: center;
              font-size: 23px;
              font-weight: bold;
              line-height: 60px;
            }
            .total-amount-blue{
              background: #20285B;
              height: 48px;
              position: relative;
              text-align: left;
              color: #fff;
              padding-right: 20px;
              width: 310px;
              font-size: 17px;
              font-weight: bold;
              padding-left: 25px;
            }
            .float-right {
                float: right;
            }
            .total-main-amount {
                background: linear-gradient(-70deg, #EC2531 84%, rgba(0, 0, 0, 0) 84%);
                position: absolute;
                height: 61px;
                width: 200px;
                top: -6px;
                color: #fff;
                font-size: 14px;
                right: 0;
                text-align: center;
                font-size: 23px;
                font-weight: bold;
                line-height: 60px;
            }
	    </style>
        <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <section class="reg-content-main">
                   
                    
                    <section class="invoice-main-sec">
                        
                        <div class="d-flex justify-content-between">
                            
                            <div class="invoice-detail">
                                <h1>Invoice</h1>
                                <label>Invoice#</label>
                                <span>{{ $bill['id'] ?? '' }}</span>
                                <br>
                                <label>Issued on</label>
                                <span>{{ $bill['created_at'] ?? '' }}</span>
                                <br>
                                <label>Recipient Name:</label>
                                <span>{{ $bill['billing_name'] ?? '' }}</span>
                                <br>
                                <label class="mt-0">
                                    {{ $bill['billing_address'] ?? '' }}<br>
                                    {{ $bill['billing_location'] ?? '' }}, {{ $bill['billing_pobox'] ?? '' }}

                                </label>
                                <br>
                                <label>Payment Mode</label>
                                <span>{{ $bill['payment_method'] ?? '' }}</span>

                            </div>

                            <div class="">
                               <div class="logo mb-3">
                                    <a href="#"><img src="{{ asset('assets/images/logo-signup.png') }}" alt=""></a>
                                </div>

                                <p>
                                    P.O Box:8943, Doha
                                </p>
                                <p>
                                    Email: support@dialectb2b.com<br>
                                    Phone: +974 44181918
                                </p>
                              
                            </div>
                            
                        </div>

                        <div class="invoice-table-header mt-5 d-flex align-items-center justify-content-end">

                            Rate

                            <div class="item-description d-flex align-items-center">
                                <span>#</span>
                                 Description
                            </div>
                        </div>

                        <table class="table invoice">
                            @foreach($bill['billing_details'] as $key => $billDetail)
                            <tr class="first-td">
                                <td>
                                    {{ $key + 1 }}  
                                </td>
                                <td style="width: 80%;">
                                    @if($billDetail['category_id'] != null)
                                    {{ $billDetail['subcategory']['name'] ?? '' }}
                                    @elseif($billDetail['package_id'] = null)
                                    {{ $billDetail['package_id'] }}
                                    @else
                                    {{ $billDetail['description'] }}
                                    @endif
                                </td>
                                <td class="amount">
                                    ${{ $billDetail['price'] }}
                                </td>
                            </tr>
                            @endforeach
                        </table>

                        <table class="table invoice-total">
                            <tr>
                                <td class="total-txt">
                                    Total  
                                </td>
                                <td class="amount">
                                    ${{ $bill['bill_amount'] }} 
                                </td>
                            </tr>

                        </table>

                        <div class="total-amount-blue mt-4 mb-4 d-flex align-items-center float-right">
                            Total
                            <div class="total-main-amount">
                                ${{ $bill['bill_amount'] }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </section>
                   
                    
                </section>
            </div>
        </section>
    </div>
	</body>
</html>