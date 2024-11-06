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
use Http;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminProfileController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function index(){
        $company_id = auth()->user()->company_id;
        
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        $package = Package::find($company->current_plan);
        return view('admin.profile.index',compact('user','company','package'));
    }

    public function edit(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        $country = Country::where('id', $company->country_id)->first();
        return view('admin.profile.edit',compact('user','company','country'));
    }
    
    
  
    public function notification(){
        $notifications = Notification::where('type',1)->where('user_id',auth()->user()->id)->get();
        $announcements = Notification::where('type',2)->where('user_id',auth()->user()->id)->get();
        return response()->json([
                'status' => true,
                'notifications' => $notifications,
                'announcements' => $announcements
            ], 200);
    }
    
    public function paymentVerification(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        return view('admin.verfication.index',compact('user','company'));
    }
    
    public function paynow(){
        $company_id = auth()->user()->company_id;
        $amt = 60;
        $paymentData = [
            "amount" => [
                "value" => number_format($amt,2),
                "currency" => "QAR"
            ],
            "description" => "Order #".$company_id,
            "redirectUrl" => "https://test.dialectb2b.com/admin/verification/success/".$company_id,
            "webhookUrl" => "https://test.dialectb2b.com/webhook/".$company_id
        ];
        
         $publicKey = 'sk_test_eae8cb43fde9ec66102b7642ec9bcd2cb861';
        
         // Make the API request to Dibsy
         $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $publicKey,
            'Content-Type' => 'application/json',
         ])->post('https://api.dibsy.one/v2/payments', $paymentData);
       
      
         // Check the response status and handle accordingly
         if ($response->successful()) {
            // Payment request was successful
            
            $responseData = $response->json();
            session(['dibsy_id' => $responseData['id']]);
            // Redirect the user to the payment gateway's URL for payment
            return redirect($responseData['_links']['checkout']['href']);
         } else {
            // Payment request failed
            $errorResponse = $response->json();
            // Handle and display the error to the user
            return response()->json(['error' => $errorResponse], $response->status());
         }

    }
    
    public function paymentSuccess(Request $request,$id){
        $dibsy_id = session('dibsy_id');
        
        $publicKey = 'sk_test_eae8cb43fde9ec66102b7642ec9bcd2cb861';
        
        $url = "https://api.dibsy.one/v2/payments/".$dibsy_id;
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
                            'ref_no' =>$dibsy_id,
                            'status' => 0	
                       ]);
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

