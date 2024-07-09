@php
   $role = auth()->user()->role;
   if($role == 1){
       $extends = 'admin.layouts.app';
       $header = 'admin.layouts.header';
   }
   else if($role == 2){
       $extends = 'procurement.layouts.app';
       $header = 'procurement.layouts.header';
   }
   else if($role == 3){
       $extends = 'sales.layouts.app';
       $header = 'sales.layouts.header';
   }
@endphp

@extends($extends)
@section('content')

    <!-- Header Starts -->
    @include($header)
    <!-- Header Ends -->
    
        <div class="container-fluid reg-bg">
        <section class="container">
            <div class="row registration">
                <section class="reg-content-main">
                   
                    
                    <section class="invoice-main-sec">
                        
                        <div class="d-flex justify-content-between">
                            
                            <div class="invoice-detail">
                                <h1>Invoice</h1>
                                <label>Invoice#</label>
                                <span>{{ $bill->id }}</span>

                                <label>Issued on</label>
                                <span>{{ $bill->created_at }}</span>

                                <label>Recipient Name:</label>
                                <span>{{ $bill->billing_name  }}</span>
                                <label class="mt-0">
                                    {{ $bill->billing_address }}<br>
                                    {{ $bill->billing_location }}, {{ $bill->billing_pobox }}

                                </label>
                                <br>
                                <label>Payment Mode</label>
                                <span>{{ $bill->payment_method }}</span>

                            </div>

                            <div class="">
                               <div class="logo mb-3">
                                    <a href="#"><img src="{{ asset('assets/images/logo-signup.png') }}" alt=""></a>
                                </div>

                                <p>
                                    P.O Box:8943, Doha<br>
                                  
                                </p>
                                <p>
                                    Email: support@dialectb2b.com<br>
                                    Phone: +974 44181918
                                </p>
                                 <div class="d-flex justify-content-center align-item-center pb-4">
                                    <a href="{{ route('subscription.download-bill',$transaction->id) }}" class="print-summry">Download Bill</a>
                                </div>
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
                            @foreach($bill->billing_details as $key => $billDetail)
                            <tr class="first-td">
                                <td>
                                    {{ $key + 1 }}  
                                </td>
                                <td>
                                    @if($billDetail->category_id != null)
                                    {{ $billDetail->subcategory->name ?? '' }}
                                    @elseif($billDetail->package_id = null)
                                    {{ $billDetail->package_id }}
                                    @else
                                    {{ $billDetail->description }}
                                    @endif
                                </td>
                                <td class="amount">
                                    ${{ $billDetail->price }}
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
                                    ${{ $bill->bill_amount }} 
                                </td>
                            </tr>

                        </table>

                        <div class="total-amount-blue mt-4 mb-4 d-flex align-items-center float-right">
                            Total
                            <div class="total-main-amount">
                                ${{ $bill->bill_amount }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </section>
                   
                    
                </section>
            </div>
        </section>
    </div>
  

@endsection