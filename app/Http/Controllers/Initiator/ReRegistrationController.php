<?php

namespace App\Http\Controllers\Initiator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Initiator\SignUpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\RegistrationToken;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SendGridEmailService;

class ReRegistrationController extends Controller
{
    
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function registrationProcess($token){
        $data = RegistrationToken::where('token', $token)->firstOrFail();
        if(!$data){
            return redirect('/');
        }
        $company = Company::find($data->company_id);
        //Cache::put('company', $company);
        session()->put('company', $company);

        $otp = rand(100000, 999999);
        $company['otp'] = $otp;
        //Cache::put($company->email, $company, now()->addMinutes(20));
        session()->put($company->email, $company);
        session()->put('expires_at', now()->addMinutes(20));
        
        

        try {
            
             $details = [
                'subject'	=>'DialectB2B Registration Process.',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$company->name ?? 'User,</p>',
                'introduction' => "<p>Good day!<br>We have received a request for email address verification from Dialectb2b.com registration.</p>",
                'body' => "<p>Your OTP (One Time Password):-</p>",
                'closing' => "<p>Please enter this passcode on the Dialectb2b.com registration page to verify your email.<br>
                                 Please note that this OTP is valid only for the mentioned transaction and cannot be used for any other transaction.<br>
                                 Your OTP is confidential. Please do not share it with anyone.<br>
                                 We appreciate your time and attention to this matter. If you have any questions, please contact our customer care through the chat box.
                                </p>",
                'otp' => $otp,
                'link' => null,
                'link_text' => null,
                "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
            ];
            
            $subject	= 'Dialectb2b Registration Process.';
            $htmlBody = view('email.common',compact('details'))->render();
            
            //$result = $this->sendGridEmailService->send($user['email'], $subject, $htmlBody, true);
           
            \Mail::to($company->email)->send(new \App\Mail\CommonMail($details));
            return redirect()->route('registration.reVerify');
        } catch (\Throwable $th) {
            return redirect()->route('registration.reVerify');
        }    
    }

    public function verify(){
        //$company = Cache::get('company');
        $company = session('company');
        return view('initiator.registration-otp',compact('company'));
    }

    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required|max:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->email;
            //$user = Cache::get($email);
            $user = session($email);

            //$otp_count = Cache::get('otp_count') + 1;
            $otp_count = session('otp_count') + 1;

            //Cache::put('otp_count', $otp_count);
            session()->put('otp_count', $otp_count);
            if($otp_count > 3){
                session()->forget('otp_count');
                return response()->json([
                    'status' => false,
                    'type' => 1,
                    'message' => 'Exceeded maximum attempts',
                ], 422);
            }

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'type' => 1,
                    'message' => 'OTP expired or does not exist',
                ], 422);
            }

            if ($user['otp'] != $request->otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }

            $checkCompanyExists = Company::where('email',$user['email'])->first();

            if(!$checkCompanyExists){
                return response()->json([
                    'status' => false,
                    'message' => 'Company does not exists!'
                ], 404);
            }
            
            // Clear the OTP from cache
            //Cache::forget($email);
            session()->forget('company');
            session()->forget('otp_count');

            //Cache::put('company', $checkCompanyExists);
            session()->put('company', $checkCompanyExists);
            
            return response()->json([
                'status' => true,
                'company' => $checkCompanyExists,
                'message' => 'Email Verified!'
            ], 200);
   

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


}