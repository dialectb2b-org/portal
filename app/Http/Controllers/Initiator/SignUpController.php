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
use App\Models\Country;
use App\Models\CompanyUser;
use App\Models\Document;
use App\Models\RegistrationToken;
use App\Models\Region;
use App\Models\Package;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SendGridEmailService;
use App\Events\SalesUserCreated;

class SignUpController extends Controller
{
    
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function index(){
        $countries = Country::where('status',1)->get();
        return view('initiator.sign-up',compact('countries'));
    }

    public function storeAndVerify(SignUpRequest $request){
        try {
            $otp = rand(100000, 999999);
            $user = $request->validated();
            $user['otp'] = $otp;
            session()->put($request->email, $user);
            session()->put('expires_at', now()->addMinutes(5));
            
            $details = [
                'subject'	=>'Dialectb2b Registration Process.',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$user['name'] ?? 'User,</p>',
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

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }    
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

            $details = [
                'subject'	=>'Dialectb2b Registration Process.',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$user['name'] ?? 'User,</p>',
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

    public function verify(){
        return view('initiator.sign-up-otp');
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
                $newCompany = Company::create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'country_id' => $user['country_id'],
                    'country_code' => $user['country_code'],
                    'phone' => $user['mobile'],
                    'is_approved' => 0,
                    'current_plan' => 1
               ]);

               // Clear the OTP from cache
                //Cache::forget($email);
                session()->forget($email);
                session()->forget('otp_count');

                //Cache::put('company', $newCompany);
                session()->put('company', $newCompany);

                $plaintext = Str::random(32);
                $token = RegistrationToken::create([
                        'company_id' => $newCompany->id,
                        'token' => hash('sha256', $plaintext),
                        'expire_at' => now()->addDays(7),
                ]);

                $details = [
                    'subject'	=>'Complete Your Registration Now!',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$newCompany->name ?? 'User,</p>',
                    'introduction' => "<p>Good Day!<br>Dialectb2b.com is thrilled to welcome you to the world of B2B Sales & Sourcing.</p>",
                    'body' => "<p> To complete your registration, please use the link provided below at your convenience:</p>",
                    'closing' => "<p>We appreciate your time and attention to this matter. If you have any questions, please contact our customer care through the chat box.</p>",
                    'otp' => null,
                    'link' => url('registration/'.$token->token),
                    'link_text' => "Continue Registration",
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Complete Your Registration Now!';
                $htmlBody = view('email.common',compact('details'))->render();
            
                //$result = $this->sendGridEmailService->send($newCompany->email, $subject, $htmlBody, true);
            
                
                \Mail::to($newCompany->email)->send(new \App\Mail\CommonMail($details));

                return response()->json([
                    'status' => true,
                    'company' => $newCompany,
                    'message' => 'Email Verified!'
                ], 200);
            }
            
            // Clear the OTP from cache
            //Cache::forget($email);
            session()->forget($email);
            session()->forget('otp_count');

            //Cache::put('company', $checkCompanyExists);
            session()->put('company', $checkCompanyExists);
            
             $plaintext = Str::random(32);
                $token = RegistrationToken::create([
                        'company_id' => $checkCompanyExists->id,
                        'token' => hash('sha256', $plaintext),
                        'expire_at' => now()->addDays(7),
                ]);
            
              $details = [
                    'subject'	=>'Complete Your Registration Now!',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$checkCompanyExists->name ?? 'User,</p>',
                    'introduction' => "<p>Good Day!<br>Dialectb2b.com is thrilled to welcome you to the world of B2B Sales & Sourcing.</p>",
                    'body' => "<p> To complete your registration, please use the link provided below at your convenience:</p>",
                    'closing' => "<p>We appreciate your time and attention to this matter. If you have any questions, please contact our customer care through the chat box.</p>",
                    'otp' => null,
                    'link' => url('registration/'.$token->token),
                    'link_text' => "Continue Registration",
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Complete Your Registration Now!';
                $htmlBody = view('email.common',compact('details'))->render();
            
                //$result = $this->sendGridEmailService->send($checkCompanyExists->email, $subject, $htmlBody, true);
            
                
                \Mail::to($checkCompanyExists->email)->send(new \App\Mail\CommonMail($details));
                

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

    public function review(){
        $comp = session('company');
        //$comp = Cache::get('company');
        if(!$comp){
            return redirect('/');
        }
        
        $company = Company::find($comp->id);
        if($company->decleration == ''){
            return back()->with('warning','Please upload declaration file to continue.');
        }
        
        $details = [
                    'subject'	=>'Confirmation of Company Details Update',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Hello '.$company->name ?? 'User,</p>',
                    'introduction' => "<p>That's Great! Thank you for updating your company details.</p>",
                    'body' => "<p> Your company registration approval process is currently under review.</p>",
                    'closing' => "<p>If you haven't received any response from our end within 48 hours, please contact our customer care through the chat box.</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Confirmation of Company Details Update';
                $htmlBody = view('email.common',compact('details'))->render();
            
                //$result = $this->sendGridEmailService->send($company->email, $subject, $htmlBody, true);

       
        \Mail::to($company->email)->send(new \App\Mail\CommonMail($details));
        
        session()->forget('company');
        
        return view('initiator.review-verification',compact('company'));
    }

    public function onboarding($token){
        $user = CompanyUser::where('token', $token)->firstOrFail();
        if(!$user){
            return redirect('/');
        }
        return view('initiator.user-declaration',compact('user'));
    }
    
     public function activate($token){
        $user = CompanyUser::where('token', $token)->firstOrFail();
        if(!$user){
            return redirect('/');
        }
        return view('initiator.onboarding',compact('user'));
    }

    public function setPassword(Request $request){
        $request->validate([  
            'password' =>'required|confirmed|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);
       
        DB::beginTransaction();

        try {
            $user = CompanyUser::find($request->user_id);
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->save();
            
            $company_users = CompanyUser::where('company_id',$user->company_id)->whereNotNull('password')->where('status',1)->get();
            $company = Company::find($user->company_id);
            if($company_users->count('id') == 3){
                $company->status = $company->is_account_verified == 1 ? 2 : 1;
                $company->save();
            }

            $admin = CompanyUser::where('company_id',$user->company_id)->where('role',1)->first();
            $procurement = CompanyUser::where('company_id',$user->company_id)->where('role',2)->first();
            $sales = CompanyUser::where('company_id',$user->company_id)->where('role',3)->first();

            if($user->role == 4){
                
                $details = [
                    'subject'	=>'Onboarding Notification - Account Activation',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear User,</p>',
                    'introduction' => "<p>We are pleased to inform you that the Team Member successfully activated account on Dialectb2b.com under your company.</p>",
                    'body' => "<p>User Name : ".$user->name."<br>User Email : ".$user->email."</p>",
                    'closing' => "<p>If you have noticed any suspicious activity regarding the said user's joining, you have the option to deactivate the account through<br>
                    your procurement account's Team Settings & Approval feature. For any questions or assistance, please feel free to contact our customer care team via the chat box.</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Onboarding Notification - Account Activation';
                $htmlBody = view('email.common',compact('details'))->render();
                
                //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($procurement->email, $subject, $htmlBody, true);
                //$result = $this->sendGridEmailService->send($sales->email, $subject, $htmlBody, true);
                
                \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
                \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
                \Mail::to($sales->email)->send(new \App\Mail\CommonMail($details));

            }

            if($user->role == 2 || $user->role == 3){
                $user_app_role = $user->role == 2 ? 'Procurement' : 'Sales';
                
                
                $details = [
                    'subject'	=>'Onboarding Notification !',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$admin->name ?? 'User,</p>',
                    'introduction' => "<p>We are pleased to inform you that the ".$user_app_role." user successfully activated account on Dialectb2b.com.</p>",
                    'body' => "<p>User Name : ".$user->name."<br>User Email : ".$user->email."</p>",
                    'closing' => "<p>Your company account has now been activated. Please utilize the features of Dialectb2b.com <br>
                    to boost your sales and procurement activities.<br>
                    Thank you for choosing Dialectb2b.com. We look forward to serving you. If you have any questions or need <br>
                    assistance with account features, please feel free to contact our customer care team via the chat box.</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Onboarding Notification !';
                $htmlBody = view('email.common',compact('details'))->render();
                
                //$result = $this->sendGridEmailService->send($company->email, $subject, $htmlBody, true);
                
                \Mail::to($company->email)->send(new \App\Mail\CommonMail($details));

                $package = Package::find($company->current_plan);
                
                 $details = [
                    'subject'	=>'Welcome to Dialectb2b.com!',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$user->name ?? 'User,</p>',
                    'introduction' => "<p>We are excited to welcome you to the world of B2B Sales & Sourcing.</p>",
                    'body' => "<p>Your Current Package : ".$package->name."</p>",
                    'closing' => "<p>Thank you for choosing Dialectb2b.com. We look forward to serving you.<br>
                                    If you have any questions or need assistance with account features,<br>
                                    please feel free to contact our customer care team via the chat box.</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p  style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Welcome to Dialectb2b.com!';
                $htmlBody = view('email.common',compact('details'))->render();
                
                //$result = $this->sendGridEmailService->send($user->email, $subject, $htmlBody, true);
                
                \Mail::to($user->email)->send(new \App\Mail\CommonMail($details));
            }
            
            if($user->role == 3){
                event(new SalesUserCreated($user));
            }
           
            
            DB::commit(); 
            if (Auth::attempt(['email' => $user->email, 'password' => $request->password], '')) {
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
            else{
                return redirect()->intended('/');
            }
          
      
            
        }
        catch(\Exception  $e){
            DB::rollback();
            return redirect()->intended('/');
        }
       
    }

    public function getDocumentByCountry(Request $request){
        $document = Document::where('country_id',$request->id)->first();
        return response()->json([
            'status' => true,
            'document' => $document,
            'message' => 'Document Fetched!'
        ], 200);
    }

    public function getRegionByCountry(Request $request){
        $regions = Region::where('country_id',$request->id)->get();
        return response()->json([
             'status' => true,
             'regions' => $regions,
             'message' => 'Regions Fetched!'
         ], 200);
    }

    
}
