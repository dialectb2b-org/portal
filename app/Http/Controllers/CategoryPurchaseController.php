<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyActivity;
use App\Models\Cart;
use App\Models\Subscription;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\PortalSetting;
use Http;
use DB;
use Auth;
 use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class CategoryPurchaseController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $categories = Category::all();
        return view('category.index',compact('categories'));
    }
    
    public function cart(){
         try {
            $activity = Cart::with('subcategory')->where('company_id',auth()->user()->company_id)->get();
            return response()->json([
                'status' => true,
                'data' => $activity
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }    
    }
    
    public function add(Request $request){
         try {
             
            $companyServicesExist = CompanyActivity::where('activity_id',$request->id)->where('company_id',auth()->user()->company_id)->first();
            
            if($companyServicesExist){ 
                return response()->json([
                    'status' => false,
                    'message' => 'Category already exists!'
                ], 422);
            }

            $cartItemExists = Cart::where('activity_id',$request->id)->where('company_id',auth()->user()->company_id)->first();
            if(!$cartItemExists){ 
                Cart::create([
                    'activity_id' => $request->id,
                    'company_id' => auth()->user()->company_id,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Category added to Cart!'
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Category already exists!'
            ], 422);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }   
    }
    
    public function remove(Request $request,$id){
         $cartItems = Cart::find($id);
         $cartItems->delete();
         return response()->json([
                'status' => true,
                'message' => "Deleted!"
            ], 200);
    }
    
    public function orderSummary(){
        $company = Company::find(auth()->user()->company_id);
        $cartItems = Cart::with('subcategory')->where('company_id',auth()->user()->company_id)->get();
        $portal_settings = PortalSetting::first();
        return view('category.order-summary',compact('cartItems','company','portal_settings'));
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
        
        $portal_settings = PortalSetting::first();
        
        $dibsy_customer_id = auth()->user()->dibsy_customer_id;
        if($dibsy_customer_id == null || $dibsy_customer_id == '') {
            $dibsy_customer_id = $this->createDibsyCustomer();
        }
        $period = 1;
        $amount = $request->total;
        $valid_from = Carbon::now()->format('Y-m-d');
        $discountPercentage = 0;
        if($request->has('payment_period')){
            $period = $request->payment_period;
            $discountPercentage = $portal_settings->plan_discount;
        }
        $valid_to = Carbon::now()->addMonths($period);
        $payamount = $amount * $period;
        $discountAmount = ($discountPercentage / 100) * $payamount;
        $discountedRate = $payamount - $discountAmount;
        
        if(auth()->user()->dibsy_customer_id != null || auth()->user()->dibsy_customer_id != '' || $request->payment_token != ''){
            $subscription = Subscription::create([
                'company_id' => auth()->user()->company_id,
                'user_id' => auth()->user()->id,
                'subscription_type' => 3, // category purchase
                'package_id' => null,
                'period_type' => $period == 1 ? 1 : 12,
                'rate' => number_format($discountedRate,2),
                'valid_from' => $valid_from,
                'valid_to' => $valid_to,
                'description' => 'Category Subscription',
                'card_token' => $request->payment_token
            ]);
            
            $paymentData = [
                "amount" => [
                    "value" => number_format($discountedRate,2,'.', ''),
                    "currency" => "QAR"
                ],
                "sequenceType" => "recurring",
                "method" => "creditcard",
                "customerId" => auth()->user()->dibsy_customer_id,
                "cardToken" => $request->payment_token,
                "description" => "Order #".$subscription->id,
                "redirectUrl" => "https://portal.simbillsoft.in/category-purchase/payment-response/".$subscription->id,
                "webhookUrl" => "https://portal.simbillsoft.in/category-purchase/payment-webhook/".$subscription->id,
                "metadata" => [
                    "name" => $request->name,
                    "email" => $request->email,
                    "mobile" => $request->code.$request->mobile,
                    "address" => $request->address,
                    "location" => $request->location,
                    "pobox" => $request->pobox
                ]
            ];
           
            $publicKey = config('dibsy.private_key');
            
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
                //dd($errorResponse);
                return response()->json(['error' => $errorResponse], $response->status());
             }
        }
        else{
            return back();
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
                   $cartItems = Cart::where('company_id',auth()->user()->company_id)->get();
                   $valid_to = Carbon::now()->addMonths($subscription->period_type);
                   
                   foreach($cartItems as $cartItem){
                       
                        CompanyActivity::create([
                            'activity_id' => $cartItem->activity_id,
                            'company_id' => auth()->user()->company_id,
                            'is_previlaged' => 0,
                            'status' => 1,
                            'expiry_date' => $valid_to,
                            'user_id' => auth()->user()->id
                        ]);
                   }
                   
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
                        
                    foreach($cartItems as $cartItem){    
                        $billingDetails = BillingDetail::create([
                                'billing_id' => $billing->id,
                                'description' => 'Category Subscription',
                                'category_id' => $cartItem->activity_id,
                                'package_id' => null,
                                'price' => 59, // TODO: change to dynamic
                                'discount' => 0	
                            ]);   
                    }
                   
                   Cart::where('company_id',auth()->user()->company_id)->delete();
                   
                   $subscription->update([
                       'payment_status' => 1,
                       'card_no' => $data['details']['cardNumber'],
                       'card_user' => $data['details']['cardHolder'],
                       'billing_id' => $billing->id
                    ]);
                    
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
                  
                    
                    //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                    
                    \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                    
                    $billingDetails = BillingDetail::where('billing_id',$billing->id)->get();
                    //dd($billingDetails);
                    $html = '';
                     foreach($billingDetails as $cartItem){    
                        $subcategory = SubCategory::find($cartItem->category_id); 
                       // dd($subcategory);
                        $html.='<li>'.$subcategory->name ?? ''.'</li>';
                    }
        
                    $details = [
                        'subject'	=>'Business Categories Added to Company Profile',
                        'salutation' => '<p style="text-align: left;font-weight: bold;">Dear Customer,</p>',
                        'introduction' => "<p>We are excited to inform you that ".$company->name." has successfully added the following additional business categories on Dialectb2b.com:</p>",
                        'body' => "<p>".$html."</p>",
                        'closing' => "<p>By adding these categories, your company has enhanced its opportunities to receive business opportunities.<br>
                                        Thank you for choosing Dialectb2b.com. We are committed to providing you with the best services.
                                     </p>",
                        'otp' => null,
                        'link' => null,
                        'link_text' => null,
                        "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                    ];
                    
                     //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                     //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                     //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
                    
                    \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                     \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
                      \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));
                    
                   
                return view('category.success',compact('data','subscription'));
            }
            else{
                return view('admin.verfication.failure',compact('data'));
            }
        } else {
            return view('admin.verfication.failure');
            //return response()->json(['error' => 'API request failed'], $response->status());
        }
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
    
    public function unsubsubscribeCategory(){
        $categories = auth()->user()->paid_activities;
        return view('category.unsubscribe',compact('categories'));
    }
    
    public function unsubsubscribeCategorySave(Request $request){
         $validator = Validator::make($request->all(), [
            'activity_id' => 'required|array|min:1',
        ]);
    
        if ($validator->fails()) {
            // Handle validation errors
            return back()->with('error','Select categories you want to unsubscribe');
        }
        
        foreach($request->activity_id as $activity){
                CompanyActivity::where([
                    'activity_id' => $activity,
                    'company_id' => auth()->user()->company_id,
                    'user_id' => auth()->user()->id,
                     ])->update(['status' => 2]);
        }
        
         return redirect()->route('profile.index')->with('success','Category Unsubscribed!');
    }
    
}