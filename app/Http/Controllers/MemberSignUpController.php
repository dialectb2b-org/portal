<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OTP;
use App\Http\Requests\CheckGuestRequest;
use App\Http\Requests\GuestInfoRequest;
use App\Models\Country;
use App\Models\Company;
use App\Models\CompanyUser;
use Auth;
use DB;
use App\Services\SendGridEmailService;

class MemberSignUpController extends Controller
{
    
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function index()
    {
        $countries = Country::all();
        return view('guest.signup',compact('countries'));
    }

    public function checkSignup (GuestInfoRequest $request){
        DB::beginTransaction();
        try{
            $company = Company::join('company_documents','company_documents.company_id','companies.id')
                            ->where('company_documents.doc_number',$request->company_id)
                            ->where('companies.country_id',$request->country_id)
                            ->first();

            if(!$company){
                return response()->json([
                    'status' => false,
                    'message' => 'Company Not Found!',
                ], 404);
            }                
            session()->put('guest_company', $company);                
            DB::commit();
            
            return response()->json([
                'status' => true,
                'data' => $company,
                'message' => 'Company Found!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function info(Request $request){
        DB::beginTransaction();
        try{
            $comp = session('guest_company');
            $company = Company::findOrFail($comp->company_id);
            if(!$company){
                return response()->json([
                    'status' => false,
                    'type' => 'nocompany',
                    'message' => 'Company Not Found!',
                ], 404);
            }     
            return view('guest.info',compact('company'));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }    
    }

    public function checkInfo(CheckGuestRequest $request){
        DB::beginTransaction();
        try{
            $comp = session('guest_company');
            $company = Company::findOrFail($comp->company_id);
            if(!$company){
                return response()->json([
                    'status' => false,
                    'type' => 'nocompany',
                    'message' => 'Company Not Found!',
                ], 404);
            }     
            if(!$company->domain){
                return response()->json([
                    'status' => false,
                    'type' => 'nodomain',
                    'message' => 'Domain Not Found!',
                ], 404);
            }     

            $allowed_domains = array($company->domain);
            $parts = explode('@', $request->email);
            $domain = array_pop($parts);
            if ( ! in_array($domain, $allowed_domains)){
                return response()->json([
                    'status' => false,
                    'type' => 'nocompanydomain',
                    'message' => 'Invalid Domain!', 
                ], 404);
            }

            $otp = rand(100000, 999999);
            $user = $request->validated();
            $user['otp'] = $otp;
            session()->put($request->email, $user);
            session()->put('expires_at', now()->addMinutes(5));

              $details = [
                'subject'	=>'Dialectb2b Registration Process.',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$user['name'] ?? 'User,</p>',
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
            
            \Mail::to($user['email'])->send(new \App\Mail\CommonMail($details));
               
            session()->put('otp_count', 0);
            return response()->json([
                'status' => true,
                'message' => 'OTP Send!',
                'user' => $user,
                'otp' => $otp
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }    
    }

    public function verify(Request $request){
        
        $comp = session('guest_company');
        $company = Company::find($comp->id);
        return view('guest.verify',compact('company'));
    }

    public function resendOtp(Request $request){
        $email = $request->email;
        $user = session($email);

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Request Timeout!',
            ], 404);
        }

        try {

            $otp = rand(100000, 999999);
            $user['otp'] = $otp;
            session()->put($email, $user);
            session()->put('expires_at', now()->addMinutes(5));

            Mail::to($user['email'])->queue(new OTP($otp));

            return response()->json([
                'status' => true,
                'message' => 'OTP Generated Successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }    
    }

    public function checkOtp(Request $request){
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

            $comp = session('guest_company');
            $company = Company::findOrFail($comp->id);

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
                ], 422 );
            }

            if ($user['otp'] != $request->otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }
            
            // Clear the OTP from cache
            //Cache::forget($email);
            session()->forget($email);
            session()->forget('otp_count');

            //Cache::put('company', $checkCompanyExists);
            
            session()->put('user', $user);

            return response()->json([
                'status' => true,
                'company' => $company,
                'user' => $user,
                'message' => 'Email Verified!'
            ], 200);
   

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function declaration (Request $request){
        $comp = session('guest_company');
        $user = session('user');
        $company = Company::findOrFail($comp->company_id);
        return view('guest.declaration',compact('company','user'));
    }

    public function createProfile (Request $request){
        $comp = session('guest_company');
        $user = session('user');
        $company = Company::findOrFail($comp->company_id);
        return view('guest.create-profile',compact('company','user'));
    }

    public function collectProfile(Request $request){

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:company_users,email',
            'designation' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13|unique:company_users,mobile,',
            'landline' => 'different:mobile,extension|nullable|digits_between:4,13|unique:company_users,landline',
            'extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);

        $comp = session('guest_company');
        $company = Company::findOrFail($comp->company_id);

        $companyuser = [
            'company_id'   => $company->id,
            'role' => $request->role,
            'name' => $request->name,
            'country_code' => '+974',
            'mobile' => $request->mobile,
            'landline' => $request->landline,
            'extension' => $request->extension,
            'designation' => $request->designation,
            'email' => $request->email,
            'approval_status' => 1,
        ];

        session()->put('user', $companyuser);
       
        return redirect()->route('member.activate');
        
    }

    public function activate(Request $request){
        $comp = session('guest_company');
        $company = Company::findOrFail($comp->company_id);
        $companyuser = session('user');
        return view('guest.set-password',compact('company','companyuser'));
    }

    public function setPassword(Request $request){
        $request->validate([  
            'password' =>'required|confirmed|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);
        
        DB::beginTransaction();

        try {

            $comp = session('guest_company');
            $company = Company::findOrFail($comp->company_id);
            $user = session('user');
            $companyuser = CompanyUser::create([
                'company_id'   => $company->id,
                'role' => $user['role'],
                'name' => $user['name'],
                'country_code' => '+974',
                'mobile' => $user['mobile'],
                'landline' => $user['landline'],
                'extension' => $user['extension'],
                'designation' => $user['designation'],
                'email' => $user['email'],
                'approval_status' => 1,
                'password' => Hash::make($request->password),
                'status' => 1
            ]);
            
             $admin = CompanyUser::where('company_id',$companyuser->company_id)->where('role',1)->first();
            $procurement = CompanyUser::where('company_id',$companyuser->company_id)->where('role',2)->first();
            $sales = CompanyUser::where('company_id',$companyuser->company_id)->where('role',3)->first();

                
                $details = [
                    'subject'	=>'Onboarding Notification - Account Activation',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear User,</p>',
                    'introduction' => "<p>We are pleased to inform you that the (Team Member) successfully activated account on Dialectb2b.com under your company.</p>",
                    'body' => "<p>User Name : ".$companyuser->name."<br>User Email : ".$companyuser->email."</p>",
                    'closing' => "<p>If you have noticed any suspicious activity regarding the said user's joining, you have the option to deactivate the account through<br>
                    your procurement account's Team Settings & Approval feature. For any questions or assistance, please feel free to contact our customer care team via the chat box.</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Onboarding Notification - Account Activation';
                $htmlBody = view('email.common',compact('details'))->render();
                
                //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
                
                \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
                \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));
                
            session()->forget('guest_company');
            session()->forget('user');
            DB::commit(); 
            if (Auth::attempt(['email' => $companyuser->email, 'password' => $request->password], '')) {
                $user = auth()->user();
                //$user->update(['token'=>'']);
                if($user->role == 1){
                    return redirect()->intended('admin/dashboard')
                    ->withSuccess('Signed in');
                }  
                elseif($user->role == 2){
                    return redirect()->intended('procurement/dashboard')
                    ->withSuccess('Signed in');
                }  
                elseif($user->role == 3){
                    return redirect()->intended('sales/dashboard')
                    ->withSuccess('Signed in');
                }  
                elseif($user->role == 4){
                    return redirect()->intended('member/dashboard')
                    ->withSuccess('Signed in');
                }  
                else{
                    return back();
                }
                
            }
      
            return back();
        }
        catch(\Exception  $e){
            DB::rollback();
            return redirect()->intended('/');
        }
       
    }
    
    
}
