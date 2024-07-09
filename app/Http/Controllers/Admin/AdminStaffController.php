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
use App\Models\Country;
use App\Models\Enquiry;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\SendGridEmailService;


class AdminStaffController extends Controller
{
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->middleware('auth');
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function enableDisable(Request $request){
        $company_id = auth()->user()->company_id;
        DB::beginTransaction();
        try{
            $user = CompanyUser::find($request->id);
            $status = $user->status == 1 ? 0 : 1;
            $user->update(['status' => $status]);

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Updated Status',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request, $id){
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $staff = CompanyUser::find($id);
        $country = Country::where('id',$company->country_id)->first();
        return view('admin.staff.edit',compact('staff','country'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:company_users,email,'.$id,
            'designation' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13|unique:company_users,mobile,'.$id,
            'landline' => 'different:mobile,extension|nullable|digits_between:4,13',
            'extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);

        DB::beginTransaction();
        try{

            $company_id = auth()->user()->company_id;
            $company = Company::find($company_id);

            $companyuser = CompanyUser::find($id);
            
             // Store the original values
            $originalValues = $companyuser->getOriginal();
            // Update the attributes
            $companyuser->name = $request->name;
            $companyuser->mobile = $request->mobile;
            $companyuser->landline = $request->landline;
            $companyuser->extension = $request->extension;
            $companyuser->designation = $request->designation;
            $companyuser->email = $request->email;
            $companyuser->save();
            
          
            
            if ($companyuser->email != $originalValues['email']) {
                $plaintext = Str::random(32);
                $companyuser->update([
                               'password' => null,
                               'token' => hash('sha256', $plaintext),
                               ]);
                               
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
                                    If you have any questions or need assistance during the activation process,<br>
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
                
                // Mail to replaced user
                
                $details = [
                    'subject'	=>'Notification: User Change',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$originalValues['name'] ?? 'User,</p>',
                    'introduction' => "<p>This is to inform you that there has been a change in your role within our system.  </p>",
                    'body' => "<p>The admin has replaced you as ".$role." user. Please be aware of this update.</p>",
                    'closing' => "<p>If you have any questions or need further assistance, please feel free to contact your Company Admin (".$admin->name.", email: ".$admin->email.").</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                $subject	= 'Welcome to Dialectb2b.com - Activate Your Account Now!';
                $htmlBody = view('email.common',compact('details'))->render();
            
            //$result = $this->sendGridEmailService->send($originalValues['email'], $subject, $htmlBody, true);
    
                \Mail::to($originalValues['email'])->send(new \App\Mail\CommonMail($details));
            }
               
            DB::commit();                
            return redirect()->route('admin.dashboard')->with('success','updated!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.dashboard')->with('error','Something went wrong!');
        }
    }
    
    public function updateProfilePic(Request $request){
        DB::beginTransaction();
        try{
            $user = CompanyUser::find($request->staff_id);
            $oldProfilePicturePath = $user->profile_image;
            $company = Company::find($user->company_id);
            $folder = 'uploads/'.$company->id;
            
            
            // Delete the old profile picture
            if ($oldProfilePicturePath) {
                Storage::disk('public')->delete($oldProfilePicturePath);
                // Or use File::delete(public_path('storage/' . $oldProfilePicturePath));
            }
            
            if ($request->hasFile('logo_file')) {
                $logo = $request->file('logo_file');
                $originalName = $logo->getClientOriginalName();
                $fileName = $user->id.date('Ymdhis').'profile.' . $logo->getClientOriginalExtension();
                //$filePath = $logo->storeAs($folder, $fileName);
                $filePath = $logo->move(public_path($folder), $fileName);
            }
            
            $user->update(['profile_image' => $folder.'/'.$fileName]);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $company,
                'filepath' => asset($folder.'/'.$fileName),
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createSales(Request $request){
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $admin = CompanyUser::where('company_id',$company_id)->where('role',1)->first();
        $procurement = CompanyUser::where('company_id',$company_id)->where('role',2)->first();
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $country = Country::where('id',$company->country_id)->first();
        return view('admin.staff.create-sales',compact('admin','procurement','sales','country'));
    }

    public function createSalesAccount(Request $request){
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:company_users,email,',
            'designation' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13|unique:company_users,mobile,',
            'landline' => 'different:mobile,extension|nullable|digits_between:4,13|unique:company_users,landline',
            'extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $plaintext = Str::random(32);
        $companyuser = CompanyUser::create([  
                                'company_id'   => $company_id,
                                'role' => 3,
                                'name' => $request->name,
                                'mobile' => $request->mobile,
                                'landline' => $request->landline,
                                'extension' => $request->extension,
                                'designation' => $request->designation,
                                'email' => $request->email,
                                'approval_status' => 1,
                                'token' => hash('sha256', $plaintext),
                            ]);

            if($companyuser->password == ''){

                $details = [
                    'name' => $companyuser->name,
                    'subject' => 'DialectB2B Registration Process.',
                    'body' => '<p>We, Dialectb2b.com, are happy to let you know that,your organization successfully created account with us. Please complete your account training from the below link.</p>',
                    'link' => url('onboarding/' . $companyuser->token),
                ];
    
                \Mail::to($companyuser->email)->send(new \App\Mail\CommonMail($details));
            }
    
            return redirect()->route('admin.dashboard');                   
    }
    
    public function memberProfile ($id){
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $staff = CompanyUser::find($id);
        $country = Country::where('id',$company->country_id)->first();
        $expiredPro = Enquiry::where('is_completed',1)->where('from_id',$staff->id)->count();
        $closedPro = Enquiry::where('is_completed',0)->where('from_id',$staff->id)->count();
        $openPro = Enquiry::where('from_id',$staff->id)->count();
        return view('admin.member.profile',compact('staff','country','expiredPro','closedPro','openPro'));
    }

}

