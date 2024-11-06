<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyDocument;
use App\Models\Country;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\PortalSetting;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use PDF;
use App\Services\SendGridEmailService;


class SubscriptionController extends Controller
{
    
    protected $sendGridEmailService;
    
    public function __construct(SendGridEmailService $sendGridEmailService) 
    {
        $this->middleware('auth');
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function index()
    {
        $company_id = auth()->user()->company_id;
        $company = Company::with('current_package')->find($company_id);
        $transactions = Subscription::where('company_id',$company_id)->get();
        $portal_settings = PortalSetting::first();
        return view('subscription.plans',compact('company','transactions','portal_settings'));
    }
    
    public function history()
    {
        $company_id = auth()->user()->company_id;
        $company = Company::with('current_package')->find($company_id);
        $transactions = Subscription::where('company_id',$company_id)->get();
        return view('subscription.index',compact('company','transactions'));
    }
    
    public function viewBill($id){
        $company_id = auth()->user()->company_id;
        $company = Company::with('current_package')->find($company_id);
        $transaction = Subscription::with('company','package')->find($id);
        $bill = Billing::with('billing_details')->where('id', $transaction->billing_id)->first();
        return view('subscription.view-bill',compact('transaction','bill'));
    }
    
    public function downloadBill($id){
        $company_id = auth()->user()->company_id;
        $company = Company::with('current_package')->find($company_id);
        $transaction = Subscription::with('company','package')->find($id);
        $bill = Billing::with('billing_details')->where('id', $transaction->billing_id)->first()->toArray();
        //return view('subscription.download-bill',compact('transaction','bill'));
         $data = [
            'transaction' => $transaction,
            'bill' => $bill,
            'company' => $company,
        ];
        $pdf = PDF::loadView('subscription.download-bill', $data);
        return $pdf->download('subscription.pdf');
    }
    
    public function subscriptionHistory()
    {
        $company_id = auth()->user()->company_id;
        $company = Company::with('current_package')->find($company_id);
        $transactions = Subscription::where('company_id',$company_id)->get();
        //return view('subscription.download-subscription-history',compact('company','transactions'));
        $data = [
            'transactions' => $transactions,
            'company' => $company,
        ];
        $pdf = PDF::loadView('subscription.download-subscription-history', $data);
        return $pdf->download('subscription.pdf');
    }
    
    public function plans(){
         $company_id = auth()->user()->company_id;
         $company = Company::with('current_package')->find($company_id);
         $portal_settings = PortalSetting::first();
         if($company->plan_validity <= date('Y-m-d')){
            $transactions = Subscription::where('company_id',$company_id)->get();
            return view('subscription.plans',compact('company','transactions','portal_settings'));
         }
         return view('subscription.plans',compact('company','portal_settings'));
    }
    
    public function billingSummary(Request $request){
        $package = Package::find($request->plan);
        $company = Company::find(auth()->user()->company_id);
        $portal_settings = PortalSetting::first();
        if($package->rate <= 0){
             Company::find(auth()->user()->company_id)->update(['current_plan_status' => 2]);
             $subscription = Subscription::latest()->first();
             $subscription->update(['cancel_reason' => 'switched to basic']);  
             
              $user = auth()->user();
                    
        $admin = CompanyUser::where('company_id',$user->company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$user->company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$user->company_id)->where('role',3)->first();
        $company = Company::where('id',$user->company_id)->first();
        $package = Package::find($subscription->package_id);
        
         $details = [
                'subject'	=>'Confirmation: Subscription Cancellation',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Dear User,</p>',
                'introduction' => "<p>We regret to inform you that your subscription on Dialectb2b.com has been successfully canceled upon request.</p>",
                'body' => "<p>lease note that your access to standard features and benefits will remain available until the end of the current subscription period.<br>
                If you have any questions or require further assistance, please don't hesitate to contact our customer support team via chat assistance.</p>",
                'closing' => "<p>Thank you for being a part of Dialectb2b.com. We appreciate your support and hope to have the opportunity to serve you again in the future.</p>",
                'otp' => null,
                'link' => null,
                'link_text' => null,
                "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
            ];
            
            $subject	= 'Confirmation: Subscription Cancellation';
            $htmlBody = view('email.common',compact('details'))->render();
            
              //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
               //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
            
            \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
            \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
            \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));
                   
            return redirect()->route('subscription');       
        }
        return view('subscription.order-summary',compact('package','company','portal_settings'));
    }
    
    public function makePayment(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'code' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13',
            'address' => 'required|string|max:50',
            'pobox' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'payment_token' => 'required|string'
        ]);
        
        $valid_from = Carbon::now()->format('Y-m-d');
        $valid_to = Carbon::now()->addMonths(1);
        $package = Package::find($request->plan);
        $portal_settings = PortalSetting::first();
        
        $dibsy_customer_id = auth()->user()->dibsy_customer_id;
        if($dibsy_customer_id == null || $dibsy_customer_id == '') {
            $dibsy_customer_id = $this->createDibsyCustomer();
        }
        
        $period = 1;
        $discountPercentage = 0;
        $rate = $package->rate;
        if($request->has('period')){
            $period = $request->period;
            $discountPercentage = $portal_settings->plan_discount;
            $valid_to = Carbon::now()->addMonths($request->period);
        }
        
        $priceforperiod = $rate * $period;
        $discountAmount = ($discountPercentage / 100) * $priceforperiod;
        $discountedRate = $priceforperiod - $discountAmount;
        
        if(auth()->user()->dibsy_customer_id != null || auth()->user()->dibsy_customer_id != '' || $request->payment_token != ''){
        
            $subscription = Subscription::create([
                    'company_id' => auth()->user()->company_id,
                    'user_id' => auth()->user()->id,
                    'subscription_type' => 2, // portal subscription
                    'description' => 'Portal Subscription',
                    'package_id' => $package->id,
                    'period_type' => $period == 1 ? 1 : 2,
                    'rate' => number_format($discountedRate,2),
                    'valid_from' => $valid_from,
                    'valid_to' => $valid_to,
                    'card_token' => $request->payment_token
                ]);
       
            $paymentData = [
                "amount" => [
                    "value" => number_format($discountedRate,2),
                    "currency" => "QAR"
                ],
                "sequenceType" => "recurring",
                "method" => "creditcard",
                "customerId" => auth()->user()->dibsy_customer_id,
                "cardToken" => $request->payment_token,
                "description" => "Order #".$subscription->id,
                "redirectUrl" => "https://test.dialectb2b.com/subscription/order/".$subscription->id,
                "webhookUrl" => "https://test.dialectb2b.com/subscription/payment-webhook/".$subscription->id,
                "metadata" => [
                        "name" => $request->name,
                        "email" => $request->email,
                        "mobile" => $request->code.$request->mobile,
                        "address" => $request->address,
                        "location" => $request->location,
                        "pobox" => $request->pobox,
                        "amount" => $priceforperiod,
                        "discount" => $discountAmount
                    ]
            ];
        
             $publicKey = config('dibsy.public_key');
            
             // Make the API request to Dibsy
             $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $publicKey,
                'Content-Type' => 'application/json',
             ])->post('https://api.dibsy.one/v2/payments', $paymentData);
       
      
             // Check the response status and handle accordingly
             if ($response->successful()) {
                // Payment request was successful
                
                $responseData = $response->json();
                
                $subscription->update([
                        'payment_mode' => $responseData['method'],
                        'payment_id' => $responseData['id']
                    ]);
                // Redirect the user to the payment gateway's URL for payment
                return redirect($responseData['_links']['checkout']['href']);
             } else {
                // Payment request failed
                $errorResponse = $response->json();
                // Handle and display the error to the user
                return response()->json(['error' => $errorResponse], $response->status());
             }
        }
        else{
            return back();
        }

    }
    
    public function order(Request $request,$id){
        
        $subscription = Subscription::find($id);
        
        $publicKey = 'sk_test_eae8cb43fde9ec66102b7642ec9bcd2cb861';
        
        $url = "https://api.dibsy.one/v2/payments/".$subscription->payment_id;
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $publicKey,
            'Content-Type' => 'application/json',
         ])->get($url);

        // You can handle the response here, for example:
        if ($response->successful()) {
            $data = $response->json(); // Convert response to JSON
            if($data['status'] == 'succeeded'){
                
                Company::find(auth()->user()->company_id)->update([
                       'current_plan' => $subscription->package_id,
                       'plan_validity' => $subscription->valid_to,
                       'current_plan_status' => 1,
                       'is_verified' => 2, // verification 
                    ]);
                    
                   
                   Payment::create([
                                'company_id' =>auth()->user()->company_id,
                                'ref_no' =>$subscription->payment_id,
                                'status' => 0	
                            ]);    
                    
                $billing = Billing::create([
                        'billing_date'	=> date('Y-m-d'),
                        'customer_id' => auth()->user()->id,	
                        'company_id' => auth()->user()->company_id,	
                        'billing_name'	=>$data['metadata']['name'], 
                        'billing_address' => $data['metadata']['address'],
                        'billing_location' => $data['metadata']['location'],
                        'billing_pobox'	=> $data['metadata']['pobox'],
                        'billing_email'	=> $data['metadata']['email'],
                        'billing_mobile' => $data['metadata']['mobile'],	
                        'bill_amount' => $data['amount']['value'],
                        'status' => 1,	
                        'payment_method' => $data['method'],	
                        'payment_reference_no' => $data['id']	
                    ]);             
                        
                    $billingDetails = BillingDetail::create([
                            'billing_id' => $billing->id,
                            'description' => 'Portal Subacription',
                            'category_id' => null,
                            'package_id' => $subscription->package_id,
                            'price' => $data['amount']['value'],
                            'discount' => 0	
                        ]);    
                    
                   $subscription->update([
                       'payment_status' => 1,
                       'card_no' => $data['details']['cardNumber'],
                       'card_user' => $data['details']['cardHolder'],
                       'billing_id' => $billing->id
                    ]);
                   
                    $subscription = Subscription::find($id);
                    
                    $billdate = \Carbon\Carbon::parse($billing->created_at)->format('F d, Y');
                    
                    $details = [
                        'subject'	=>'Invoice for Subscription from Dialectb2b.com',
                        'salutation' => '<p style="text-align: left;font-weight: bold;">Dear Customer,</p>',
                        'introduction' => "<p>Thank you for subscribing to our services.</p>",
                        'body' => "<p>Attached to this email, you will find the invoice for your subscription payment made on (".$billdate."). <br>
                                        If you have any questions or need further assistance, please feel free to contact us at support@dialectb2b.com  or call us at +974 30337071.</p>",
                        'closing' => "<p>We appreciate your business.</p>",
                        'otp' => null,
                        'link' => null,
                        'link_text' => null,
                        "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                    ];
                    
                    $subject	= 'Invoice for Subscription from Dialectb2b.com';
                    $htmlBody = view('email.common',compact('details'))->render();
                    $user = auth()->user();
                    
                    $admin = CompanyUser::where('company_id',$user->company_id)->where('role',1)->first();
                    $procurement = CompanyUser::where('company_id',$user->company_id)->where('role',2)->first();
                    $sales = CompanyUser::where('company_id',$user->company_id)->where('role',3)->first();
                    $company = Company::where('id',$user->company_id)->first();
                    $package = Package::find($subscription->package_id);
                    
                    //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                    
                    \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                    
                    $details = [
                        'subject'	=>'Upgrade to '.$package->name.' Package Confirmed!',
                        'salutation' => '<p style="text-align: left;font-weight: bold;">Dear User,</p>',
                        'introduction' => "<p>We're thrilled to inform you that ".$company->name." has upgraded its subscription to the Standard package on Dialectb2b.com!</p>",
                        'body' => "<p>With this upgrade, you'll enjoy enhanced features and benefits that will elevate your experience.<br>
                                   To explore Dialectb2b.com ".$package->name." package and its offerings, simply login to your account and visit the subscription section.</p>",
                        'closing' => "<p>Thank you for choosing Dialectb2b.com. We're committed to providing you with the best services.</p>",
                        'otp' => null,
                        'link' => null,
                        'link_text' => null,
                        "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                    ];
                    
                    $subject	= 'Upgrade to '.$package->name.' Package Confirmed!';
                    $htmlBody = view('email.common',compact('details'))->render();
                    
                      //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                       //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                        //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
                    
                    \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                    if($company->status == 1){
                        \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
                        \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));
                    }
                return view('subscription.success',compact('data','subscription','company'));
            }
            else{
                return view('subscription.failure',compact('data'));
            }
        } else {
            return view('subscription.failure',compact('data'));
            //return response()->json(['error' => 'API request failed'], $response->status());
        }
        
    }
    
     public function payment(Request $request, $id){
        $subscription = Subscription::find($id);
        
        $publicKey = 'sk_test_eae8cb43fde9ec66102b7642ec9bcd2cb861';
        
        $url = "https://api.dibsy.one/v2/payments/".$subscription->payment_id;
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $publicKey,
            'Content-Type' => 'application/json',
         ])->get($url);

        // You can handle the response here, for example:
        if ($response->successful()) {
            $data = $response->json(); // Convert response to JSON
            if($data['status'] == 'succeeded'){
               $subscription->update([
                   'payment_status' => 1,
               ]);
               
               Company::find(auth()->user()->company_id)->update([
                       'current_plan' => $subscription->package_id,
                       'plan_validity' => $subscription->valid_to
                   ]);
            }
        } else {
            //return response()->json(['error' => 'API request failed'], $response->status());
        }
    }
    
    public function cancelSubscription(Request $request){
        Company::find(auth()->user()->company_id)->update(['current_plan_status' => 2]);
        $subscription = Subscription::latest()->first();
        $subscription->update(['cancel_reason' => $request->reason]);
        
         $user = auth()->user();
                    
        $admin = CompanyUser::where('company_id',$user->company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$user->company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$user->company_id)->where('role',3)->first();
        $company = Company::where('id',$user->company_id)->first();
        $package = Package::find($subscription->package_id);
        
         $details = [
                'subject'	=>'Confirmation: Subscription Cancellation',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Dear User,</p>',
                'introduction' => "<p>We regret to inform you that your subscription on Dialectb2b.com has been successfully canceled upon request.</p>",
                'body' => "<p>lease note that your access to standard features and benefits will remain available until the end of the current subscription period.<br>
                If you have any questions or require further assistance, please don't hesitate to contact our customer support team via chat assistance.</p>",
                'closing' => "<p>Thank you for being a part of Dialectb2b.com. We appreciate your support and hope to have the opportunity to serve you again in the future.</p>",
                'otp' => null,
                'link' => null,
                'link_text' => null,
                "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
            ];
            
            $subject	= 'Confirmation: Subscription Cancellation';
            $htmlBody = view('email.common',compact('details'))->render();
            
              //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
               //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
            
            \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
            \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
            \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));
        
        return back()->with('message', 'Subscription Cancelled!');
    }
    
    public function createDibsyCustomer(){
        $payload = [
            "name" => auth()->user()->name,
            "email" => auth()->user()->email,
        ];
        
         $publicKey = config('dibsy.public_key');
        
         // Make the API request to Dibsy
         $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $publicKey,
            'Content-Type' => 'application/json',
         ])->post('https://api.dibsy.one/v2/customers', $payload);
         
         if ($response->successful()) {
            $data = $response->json();
            auth()->user()->update(['dibsy_customer_id' =>$data['id']]);
            return $data['id'];
         }
         else{
             return null;
         }
         
    }
    
}