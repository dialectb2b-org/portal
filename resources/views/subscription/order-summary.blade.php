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
    <!-- Main Content -->
    
    
    <style>
    .dibsy-component {
     background: #fff;
  box-shadow: 0px 1px 0px rgb(0 0 0 / 10%), 0px 2px 4px rgb(0 0 0 / 10%),
    0px 4px 8px rgb(0 0 0 / 5%);
  border-radius: 4px;
  padding: 13px;
  border: 1px solid #ccc; /* Add border style here */
  transition: 0.15s border-color cubic-bezier(0.4, 0, 0.2, 1);
  font-weight: 500;
    }
    /* Adds styles when the field receives focus for the first time. */

        .dibsy-component.is-touched {
          border-color: #0077ff;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the fields receive focus and removed when the fields have lost focus */
        
        .dibsy-component.has-focus {
          border-color: #0077ff;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the input values in the field are legal  */
        
        .dibsy-component.is-invalid {
          border-color: #ff1717;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Adds styles when the input values in the field are illegal */
        
        .dibsy-component.is-valid {
          border-color: green;
          transition: 0.3s border-color cubic-bezier(0.4, 0, 0.2, 1);
        }
</style>
  <section class="container-fluid pleft-77">
      <form action="{{ route('subscription.make-payment') }}" method="post">
          @csrf
        <div class="px-4 py-3">
                <h1 class="mb-4 mt-2">Order Summary</h1>
            <div class="sub-plans-main">
                <div class="row">
                    <div class="col-md-8 order-summ-content">
                        <p>By proceeding with this order, you're confirming acceptance of our terms of services. Your payment method will be securely stored and updated for continuous service.<br> Please refer to our User Agreement on the homepage for details on cancellation and refunds. Kindly note that prices are subject to change.</p>
                        <div class="mt-3">
                            <div class="row">
                                <h2 class="mb-2">Billing Address</h2>
                                <div class="col-md-12">
                                    <label>Company Name <span class="mandatory">*</span></label>
                                    <input name="name" type="text" placeholder="Company Name" class="form-control" value="{{ $company->name ?? '' }}">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input name="address" type="text" placeholder="Address" class="form-control" value="{{ $company->address ?? '' }}">
                                    @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>PO Box No<span class="mandatory">*</span></label>
                                    <input name="pobox" type="text" placeholder="PO Box No" class="form-control" value="{{ $company->pobox ?? '' }}">
                                    @error('pobox')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label>Location</label>
                                    <input name="location" type="text" placeholder="Location" class="form-control" value="{{ $company->building ?? '' }}">
                                    @error('location')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Email<span class="mandatory">*</span></label>
                                    <input name="email" type="text" placeholder="Email" class="form-control" value="{{ $company->email ?? '' }}">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <label>Mobile <span class="mandatory">*</span></label>
                                        <div class="d-flex">
                                            <input name="code" type="text" placeholder="+974" class="form-control mobile-code" value="{{ $company->country_code ?? '' }}">
                                            <input name="mobile" type="text" placeholder="Mobile" class="form-control mobile-number" value="{{ $company->phone ?? '' }}">
                                        </div>
                                        @error('mobile')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                           
                              
                            </div>
                            
                            <div class="row mt-4">
                                <h2 class="mb-2">Card Details</h2>
                                @error('payment_token')
                                <span class="text-danger">Inavlid Card Details</span>
                                @enderror
                                <div class="col-md-12">
                                    <label>Name <span class="mandatory">*</span></label>
                                    <div id="card-holder"></div>
                                    <div id="card-holder-error"></div>
                                </div>
                                <div class="col-md-12">
                                    <label>Card No<span class="mandatory">*</span></label>
                                    <div id="card-number"></div>
                                    <div id="card-number-error"></div>  
                                </div>
                                <div class="col-md-6">
                                    <label>Expiry Date<span class="mandatory">*</span></label>
                                    <div id="expiry-date"></div>
                                    <div id="expiry-date-error"></div> 
                                </div>
                                <div class="col-md-6">
                                    <label>CVC<span class="mandatory">*</span></label>
                                    <div id="verification-code"></div>
                                    <div id="verification-code-error"></div> 
                                </div>
                            </div>

                            

                        </div>
                    </div>
                
                    <div class="col-md-4 order-summ-content">
                        <div class="mt-5">
                            <div class="d-flex align-items-center justify-content-between head-summ">
                                Selected Package
                                <span>Price</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between content-summ mt-2">
                                <input type="hidden" name="plan" value="{{ $package->id }}" />
                                {{ $package->name }}
                                <span class="d-flex align-items-center">
                                    ${{ $package->rate }}
                                </span>
                            </div>
                           

                            

                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="plan-off d-flex">
                                <span>
                                    <span>Billed</span>
                                    Monthly
                                </span>
                                <input type="checkbox" name="period" id="aa3" class="add-page-check"  value="12" checked>
                                <label for="aa3" class="d-flex mt-4"></label>
                                <div class="month-year-switch">
                                    <span class="pad-top-22">
                                        Yearly
                                    </span>
                                </div>
                            </div>
                            <i class="pt-3 d-block">Save {{ $portal_settings->plan_discount }}% when you pay yearly</i>
                         
                        </div>

                        <div class="total-amount mt-4">
                            <h1 class="pb-2">Total (1)</h1>
                            <input id="total" name="total" type="hidden" value="{{ $package->rate }}"/>
                            <input id="discount" name="discount" type="hidden" value="{{ $portal_settings->plan_discount ?? 0 }}"/>
                            <div class="d-flex align-items-center justify-content-between sub-total">
                                Sub Total
                                <span class="rate-total-val">${{ number_format($package->rate,2) }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between sub-total">
                                Discount
                                <span class="discount-value">$0.00</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between total">
                                Sub Total
                                <span class="sub-total-val">${{ number_format($package->rate,2) }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p>Starting {{ date('d F, Y') }}, you will be charged $<span id="payment-amount"></span> <span id="payment-interval"></span> plus applicable taxes.<br>

                                The plan will renew automatically on an monthly basis until cancelled. To prevent charges for the upcoming month, please cancel before the renewal date.</p>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="form-group proceed-btn">
                        <a href="{{ route('subscription.plans') }}" class="btn btn-secondary" >Back</a>
                    </div>
                    <div class="form-group proceed-btn">
                        <button type="submit" class="btn btn-secondary" >Make Payment</a>
                    </div>
                </div>

            </div>
        </div>
       </form>
    </section>
    <!-- Main Content End -->
@push('scripts')
<script src="https://cdn.dibsy.one/js/dibsy-2.0.0.js"></script>
<script>

    (async () => {
        const dibsy = await Dibsy("pk_test_765f54ec5f7a51f493ca182b4ee3b0b2a9d4", { locale: "en_US" });
        
         const options = {
                styles: {
                    base: {
                        color: '#333', // Text color
                        fontFamily: 'Arial, sans-serif', // Font family
                        fontSize: '16px', // Font size
                        '::placeholder': {
                            color: '#ccc', // Placeholder color
                        },
                    },
                    invalid: {
                        color: '#ff0000', // Color for invalid input
                    },
                },
                // Other configuration options can go here
            };
        
            const cardNumber = dibsy.createComponent("cardNumber", options);
            cardNumber.mount("#card-number");
        
            const cardHolder = dibsy.createComponent("cardHolder", options);
            cardHolder.mount("#card-holder");
        
            const expiryDate = dibsy.createComponent("expiryDate", options);
            expiryDate.mount("#expiry-date");
        
            const verificationCode = dibsy.createComponent("verificationCode", options);
            verificationCode.mount("#verification-code");
        
          // Error Handling
          var cardNumberError = document.getElementById("card-number-error");
          cardNumber.addEventListener("change", function (event) {
            if (event.error && event.touched) {
              cardNumberError.textContent = event.error;
            } else {
              cardNumberError.textContent = "";
            }
          });
          
          
          // Submit Form
          const form = document.querySelector('form');

            form.addEventListener('submit', async e => {
                e.preventDefault();
            
                const { token, error } = await dibsy.cardToken();
                if (error) {
                    // Handle error here (e.g., display error message to the user)
                    console.error('Error:', error);
                } else {
                    // Tokenization successful, you can send the token to your server for further processing
                    console.log('Token:', token);
            
                    // Append the token to a hidden input field in the form and then submit the form to your server
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'payment_token';
                    tokenInput.value = token;
                    form.appendChild(tokenInput);
            
                    // Submit form to the server
                    form.submit();
                }
            });
          
           
              
          
          
            
    })();
    //const dibsy = await Dibsy("pk_test_765f54ec5f7a51f493ca182b4ee3b0b2a9d4", { locale: "en_US" });
    $(document).ready(function() {
         setTimeout(function () {
            checkSubtot();
         },1000);
    });

    
    $('.add-page-check').on('change',function(){
        checkSubtot();
    });
    
    function checkSubtot(){
        var total = $('#total').val();
        var discount_per = 0;
        //$('.add-page-check').change(function() {
            if ($('.add-page-check').is(':checked')) {
                var plan_value = total * 12;
                discount_per = $('#discount').val();
                var discountAmount = (discount_per / 100) * plan_value;
                var subtot = plan_value - discountAmount;
                $('.rate-total-val').text(plan_value.toFixed(2));
                $('.discount-value').text(discountAmount.toFixed(2));
                $('.sub-total-val').text(subtot.toFixed(2));
                $('#payment-amount').text(subtot.toFixed(2));
                $('#payment-interval').text('annually');
            } else {
                var subtot = total * 1;
                $('.rate-total-val').text(subtot.toFixed(2));
                $('.discount-value').text('0');
                $('.sub-total-val').text(subtot.toFixed(2));
                $('#payment-amount').text(subtot.toFixed(2));
                $('#payment-interval').text('monthly');
            }
       // });
    }  
      
      
</script>

 Modal 
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Important Notice</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div style="text-align: justify;">To ensure that your payments are initiated by your organization, please utilize the company's
               bank account for the transaction. Individual account purchases are considered regular purchases
               and cannot be classified as verification purposes.</div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
      $('#exampleModal').modal('show'); 
    });
</script>
@endpush
@endsection    