<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Initiator\SignUpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\Notification;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SendGridEmailService;


class AdminHomeController extends Controller
{

    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->middleware('auth');
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function index(){
        $user = auth()->user();
        $user->update(['token'=>'']);
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        if($company->status == 0){
            if($company->is_overlap == 1 && $company->is_verified != 1){
                return redirect()->route('admin.supersede.accountVerify');
            }
            if($admin->name === ''){
                return redirect()->route('admin.adminEdit');
            }
             
            // $details = [
            //     'name' => $companyuser->name,
            //     'subject' => 'Reminder to activate the user account.',
            //     'body' => '<p>Greetings From Dialectb2b.com<br>Please  find your user account activation status,<br>1. Your Profile : Active<br>Procurement :'+!$procurement ? "Inactive" : "Active"+' </br> Sales : '+!$sales ? "Inactive" : "Active"+'<br> 
            //                   Please do not hesitate to contact us if you need any assistance or have any other query in this regard through live chat support and FAQ </p>',
            //     'link' => '',
            // ];

            // \Mail::to($companyuser->email)->send(new \App\Mail\CommonMail($details));
            
            return view('admin.team-creation',compact('admin','procurement','sales'));
        }
        else{
            // Procurement Consolidated
            $expiredPro = Enquiry::where('is_completed',1)->where('from_id',$procurement->id)->count();
            $closedPro = Enquiry::where('is_completed',0)->where('from_id',$procurement->id)->count();
            $openPro = Enquiry::where('from_id',$procurement->id)->count();
            //Enquiry::where('expired_at','>=',now())->where('is_completed',0)->where('from_id',$procurement->id)->count()

            // Sales Consolidated
            $expiredSale = 0;
            $closedSale = 0;
            $openSale = 0;
            if($sales){
                $expiredSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')->where('expired_at','<',now())->where('enquiry_relations.to_id',$sales->id)->count();
                $closedSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')->where('enquiry_relations.is_replied',1)->where('enquiry_relations.to_id',$sales->id)->count();
                $openSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')->where('enquiry_relations.to_id',$sales->id)->count();
            }
            

            // Member List
            $members = CompanyUser::where('company_id',$company_id)->where('role',4)->get();
            return view('admin.dashboard',compact('company','procurement','sales','members','expiredPro','closedPro','openPro','expiredSale','closedSale','openSale'));
        }
    }
    
    public function chartData(Request $request){
         $company_id = auth()->user()->company_id;
         $company = Company::find($company_id);
         $user = CompanyUser::where('company_id',$company_id)->where('role',$request->role)->first();
         if($request->role == 2){
              $expiredSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                    ->where('expired_at','<',now())
                                    ->where('enquiries.from_id',$user->id)
                                     ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                    ->count();
              $closedSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                    ->where('is_completed',1)
                                    ->where('enquiries.from_id',$user->id)
                                     ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                    ->count();
         
              $openSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                ->where('expired_at','>=',now())->where('is_completed',0)
                                ->where('enquiries.from_id',$user->id)
                                ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                ->count();
             
         }
         else if($request->role == 3){
             $expiredSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                    ->where('expired_at','<',now())
                                    ->where('enquiry_relations.to_id',$user->id)
                                     ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                    ->count();
              $closedSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                    ->where('is_completed',1)
                                    ->where('enquiry_relations.to_id',$user->id)
                                     ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                    ->count();
         
              $openSale = Enquiry::join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                ->where('expired_at','>=',now())->where('is_completed',0)
                                ->where('enquiry_relations.to_id',$user->id)
                                ->whereBetween('enquiries.created_at',[$request->start_date,$request->end_date])
                                ->count();
         }
        
         
         return response()->json( ['open' =>  $openSale , 'closed' => $closedSale ,'expired' => $expiredSale ]);
    }

    public function complete(Request $request){
        $user = auth()->user();
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $company->update(['status' => 1]);
        return view('admin.setup.completion');
    }

    public function adminEdit (){ 
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $country = Country::where('id',$company->country_id)->first();
        return view('admin.setup.edit-admin',compact('admin','procurement','sales','country'));
    }

    public function adminUpdate(Request $request,$id){
        $request->validate([
            'admin_name' => 'required|string|max:50',
            'admin_email' => 'required|email|unique:company_users,email,'.$id,
            'admin_designation' => 'required|string|max:50',
            'admin_mobile' => 'nullable|different:landline,extension|digits_between:4,13',
            'admin_landline' => 'different:mobile,extension|nullable|digits_between:4,13',
            'admin_extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);
        
        DB::beginTransaction();
        try{

            $company_id = auth()->user()->company_id;
            $plaintext = Str::random(32);
            $companyuser = CompanyUser::find($id)->update([
                'name' => $request->admin_name,
                'mobile' => $request->admin_mobile,
                'landline' => $request->admin_landline,
                'extension' => $request->admin_extension,
                'designation' => $request->admin_designation,
                'email' => $request->admin_email,
                'approval_status' => 1,
                'token' => hash('sha256', $plaintext),
            ]);
            
 

            DB::commit();
            
            $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
            if(!$procurement){
                return redirect()->route('admin.procurementCreate');
            }

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
          DB::rollback();
          return redirect()->route('admin.dashboard');
      }         
    }

    public function procurementCreate(){
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $country = Country::where('id',$company->country_id)->first();
        return view('admin.setup.create-procurement',compact('admin','procurement','sales','country'));
    }

    public function salesCreate(){
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $country = Country::where('id',$company->country_id)->first();
        return view('admin.setup.create-sales',compact('admin','procurement','sales','country'));
    }

    public function createUpdateUser(Request $request){
        $id = $request->user_id;

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:company_users,email,'.$id,
            'designation' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13',
            'landline' => 'different:mobile,extension|nullable|digits_between:4,13', 
            'extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);

        $company_id = auth()->user()->company_id;
        $plaintext = Str::random(32);
        $company = Company::find($company_id);
        $companyuser = CompanyUser::updateOrCreate([
            'company_id'   => $company_id,
            'role' => $request->role
        ],[
            'name' => $request->name,
            'country_code' => '+974',
            'mobile' => $request->mobile,
            'landline' => $request->landline,
            'extension' => $request->extension,
            'designation' => $request->designation,
            'email' => $request->email,
            'approval_status' => 1,
            'token' => hash('sha256', $plaintext),
        ]);

        if($companyuser->password == ''){
            
            $role = $companyuser->role == 2 ? 'Procurement' : 'Sales';
               
            $admin = CompanyUser::where(['company_id' => $company->id, 'role' => 1])->first();
                
            $details = [
                    'subject'	=>'Welcome to Dialectb2b.com - Activate Your Account Now!',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$companyuser->name ?? 'User,</p>',
                    'introduction' => "<p>Dialectb2b.com is excited to welcome you to the world of B2B Sales & Sourcing. </p>",
                    'body' => "<p>".$company->name." has successfully created an account with us at Dialectb2b.com, and your <br>
                                    contact has been added  to manage (".$role." activity) by (".$admin->name.", email: ".$admin->email.").<br> 
                                    To manage your account, please activate your user account by clicking the link below:</p>",
                    'closing' => "<p>Your prompt action in submitting the necessary details is appreciated. <br>
                                    If you have any questions or need assistance during the activation process,
                                    please feel free to contact our customer care team via the chat box.<br><br>

                                    Thank you for choosing Dialectb2b.com. We look forward to serving you.
                                    </p>",
                    'otp' => null,
                    'link' => url('onboarding/' . $companyuser->token),
                    'link_text' => 'Activate Account',
                    "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Welcome to Dialectb2b.com - Activate Your Account Now!';
                $htmlBody = view('email.common',compact('details'))->render();
            
            //$result = $this->sendGridEmailService->send($companyuser->email, $subject, $htmlBody, true);
    
            \Mail::to($companyuser->email)->send(new \App\Mail\CommonMail($details));
            
            Notification::create([
                'company_id' => $company_id, 
                'user_id' => $companyuser->id,
                'type' => 1,
                'role' => $companyuser->role,
                'title' => 'Welcome to DialectB2b',
                'message' => 'Welcome to DialectB2b',
                'action' => '', 
                'action_url' => '' 
                ]);
        }
        
        $allUsersCount = CompanyUser::where('company_id',$company_id)->count();
        if($allUsersCount == 3){
            $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
            $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
            $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
            $sales_status = $sales->status == 1 ? 'Activated' : 'Pending';
            $procurement_status = $procurement->status == 1 ? 'Activated' : 'Pending';
            $details = [
                'subject'	=>'Admin Profile Updated!',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$admin->name ?? 'User,</p>',
                'introduction' => "<p>Your profile has been updated as a company admin account. You can log in to your account<br>
                                        after the successful user activation of your procurement and sales users. Here are the user details:</p>",
                'body' => "<p>Sales User: ".$sales->name.", email: ".$sales->email."(Activation Status) ".$sales_status." <br>
                              Procurement User: ".$procurement->name.", email: ".$procurement->email." ( Activation Staus) ".$procurement_status." </p>",
                'closing' => "<p>Your prompt action in submitting the necessary details is appreciated. <br>
                                If you have any questions or need assistance during the activation process,
                                please feel free to contact our customer care team via the chat box.<br><br>
                                Thank you for choosing Dialectb2b.com. We look forward to serving you.</p>",
                'otp' => null,
                'link' => url('/admin/dashboard'),
                'link_text' => 'Assign New User',
                "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
            ];
            
            $subject	= 'Admin Profile Updated!';
            $htmlBody = view('email.common',compact('details'))->render();
            
            //$result = $this->sendGridEmailService->send($admin->email, $subject, $htmlBody, true);
    
            \Mail::to($admin->email)->send(new \App\Mail\CommonMail($details));
        }

        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        
        if($procurement && !$sales){
             return redirect()->route('admin.salesCreate');
        }
        return redirect()->route('admin.dashboard');
    }

    // public function supersedeAccountVerification(){
    //     $user = auth()->user();
    //     $company_id = auth()->user()->company_id;
    //     $company = Company::find($company_id);
    //     return view('admin.supersede.account-verification',compact('company'));
    // }
    
    public function supersedeAccountVerification(){
        $user = auth()->user();
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        return view('admin.supersede.account-verification-note',compact('company'));
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }



}