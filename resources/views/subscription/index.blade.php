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
    
        <section class="container-fluid pleft-77">
            <div class="px-4 py-3">
                <div class="d-flex justify-content-between">
                    <div class="sub-billing-left">
                        <h1>Billing</h1>
                    </div>
                    <div class="busins-head d-flex justify-content-between">
                       
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <h3>Transaction History</h3>
                    <a href="{{ route('subscription.download-subscription-history') }}" class="print-summry">Print Transaction Summary</a>
                </div>

                <div class="trans-history">
                    
                    <table class="table tbl-secnd">
                        <thead>
                            <tr>
                                <th scope="col">Bill Date</th>
                                <th scope="col">Description</th>
                                <th scope="col" class="text-center">Amount($)</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-end">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>{{ $transaction->created_at }}</td>
                                <td>{{ $transaction->description ?? '' }}</td>
                                <td class="text-center">{{ $transaction->rate }}</td> 
                                <td class="text-center">{{ $transaction->payment_status == 1 ? 'success' : 'failed' }}</td> 
                                <td class="text-end">
                                    @if($transaction->payment_status == 1)
                                    <a href="{{ route('subscription.view-bill',$transaction->id) }}">View</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data Found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>


                    <div class="grand-note">
                         <span>*Note:</span>
                         <ul>
                            <li>Subsequent renewal price is subject to change based on promotions, upgrades and other factors. </li>
                            <li>Sales tax will be applicable for certain regions as per the federal mandates governing the regions.</li>
                            <li>All prices mentioned are in USD.</li>
                         </ul>
                    </div>
                


                </div>

            </div>

    </section>
  <!-- View Cancel Subscription Model -->
        <div class="modal fade bd-example-modal-lg" id="cancelSubscriptionModel" tabindex="-1" role="dialog" aria-labelledby="cancelSubscriptionModel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLongTitle">Unsubscribe</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <div class="card">
                            <div class="card-body">
                                <form action="{{ route('subscription.cancel') }}" method="post">
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="sale-prof-detail">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label>Current Plan</label>
                                                    {{ $package->name ?? '' }}
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Valid Upto</label>
                                                     {{ $company->plan_validity != null ? $company->plan_validity : 'NA' }}
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Reason for Cancellation</label>
                                                        <textarea class="form-control" name="reason" required></textarea>
                                                    </div>
                                                    <div class="col-md-12 mt-4 d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-third">Cancel Subscription</button>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>

    
    $('body').on('click','.cancel_sub',function(){
        $('#cancelSubscriptionModel').modal('show');
    });

   
</script>    
@endpush
@endsection