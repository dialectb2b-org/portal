<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Billing;
use App\Models\BillingDetail;
use Http;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Services\SendGridEmailService;


class AccountVerificationController extends Controller
{
    
    protected $sendGridEmailService;
    
    public function __construct(SendGridEmailService $sendGridEmailService) 
    {
      $this->middleware('auth');
      $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function paymentVerificationInfo(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        return view('admin.verfication.intro',compact('user','company'));
    }
    
    public function paymentVerification(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        return view('admin.verfication.index',compact('user','company'));
    }
    
    public function paynow(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'code' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13',
            'address' => 'required|string|max:50',
            'pobox' => 'required|string|max:50',
            'location' => 'required|string|max:50',
        ]);
        
        $company_id = auth()->user()->company_id;
        $amt = 60;
        
        $subscription = Subscription::create([
                            'company_id' => auth()->user()->company_id,
                            'user_id' => auth()->user()->id,
                            'subscription_type' => 1, // account verification
                            'rate' => number_format($amt,2),
                            'description' => 'Account Verification',
                        ]);
            
        $paymentData = [
            "amount" => [
                "value" => number_format($amt,2),
                "currency" => "QAR"
            ],
            "metadata" => [
                "name" => $request->name,
                "email" => $request->email,
                "mobile" => $request->code.$request->mobile,
                "address" => $request->address,
                "location" => $request->location,
                "pobox" => $request->pobox
            ],
            "description" => "Order #".$subscription->id,
            "redirectUrl" => "https://portal.simbillsoft.in/admin/verification/payment-response/".$subscription->id,
            "webhookUrl" => "https://portal.simbillsoft.in/admin/verification/payment-response-webhook/".$subscription->id
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
            return response()->json(['error' => $errorResponse], $response->status());
         }

    }
    
    public function paymentResponse(Request $request,$id){
        $subscription = Subscription::find($id);
        
        $publicKey = config('dibsy.private_key');
        
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
                       'is_verified' => 2,
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
                            'description' => 'Account Verification',
                            'category_id' => null,
                            'package_id' => null,
                            'price' => $data['amount']['value'],
                            'discount' => 0	
                        ]);    
                        
                    $subscription->update([
                       'payment_status' => 1,
                       'card_no' => $data['details']['cardNumber'],
                       'card_user' => $data['details']['cardHolder'],
                       'billing_id' => $billing->id
                    ]);
                    
                    $billdate = \Carbon\Carbon::parse($billingDetails->created_at)->format('F d, Y');
                    
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
                    //$result = $this->sendGridEmailService->send($user->email, $subject, $htmlBody, true);
                    
                    \Mail::to($user->email)->send(new \App\Mail\CommonMail($details));
                   
                return view('admin.verfication.success',compact('data'));
            }
            else{
                return view('admin.verfication.failure',compact('data'));
            }
        } else {
            return view('admin.verfication.failure',compact('data'));
            //return response()->json(['error' => 'API request failed'], $response->status());
        }
    }
    
    public function paymentData(Request $request, $id){
        \Log::stack(['single', 'slack'])->info('Webhook Called!');
    }

}

