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
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Document;
use Http;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $company_id = auth()->user()->company_id;
        
        $user = auth()->user();
        $company = Company::with('document','payment')->find($company_id);
        $package = Package::find($company->current_plan);
        return view('profile.index',compact('user','company','package'));
    }

    public function edit(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        $country = Country::where('id', $company->country_id)->first();
        return view('profile.edit',compact('user','company','country'));
    }
    
    public function update(Request $request){
        $id = auth()->user()->id;
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:company_users,email,'.$id,
            'designation' => 'required|string|max:50',
            'mobile' => 'nullable|different:landline,extension|digits_between:4,13|unique:company_users,mobile,'.$id,
            'landline' => 'different:mobile,extension|nullable|digits_between:4,13|unique:company_users,landline',
            'extension' => 'different:mobile,landline|nullable|digits_between:1,13',
        ]);
    
        DB::beginTransaction();
        try{
    
            $company_id = auth()->user()->company_id;
            $company = Company::find($company_id);
    
            $companyuser = CompanyUser::find($id)
                            ->update([  
                                'name' => $request->name,
                                'mobile' => $request->mobile,
                                'landline' => $request->landline,
                                'extension' => $request->extension,
                                'designation' => $request->designation,
                            ]);
            DB::commit();                
            return redirect()->route('profile.index')->with('success','updated!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('profile.index')->with('error','Something went wrong!');
        }
    }
    
    public function updateProfilePic(Request $request){
        DB::beginTransaction();
        try{
            $user = auth()->user();
            $company = Company::find($user->company_id);
            $folder = 'uploads/'.$company->id;
            if ($request->hasFile('logo_file')) {
                $logo = $request->file('logo_file');
                $originalName = $logo->getClientOriginalName();
                $fileName = $user->id.'profile.' . $logo->getClientOriginalExtension();
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
    
    public function changePassword(){
        return view('profile.change-password');
    }
    
    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ]);
    
        $user = Auth::user();
    
        if (Hash::check($request->current_password, $user->password)) {
            $user->update(['password' => bcrypt($request->new_password)]);
            return redirect()->route('profile.index')->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password.']);
        }
    }
    
    public function updateDocument(Request $request){
        DB::beginTransaction();
        try{
            $comp_id = auth()->user()->company_id;
            $company = Company::find($comp_id);
            $doc = Document::where('country_id',$company->country_id)->first();
            $folder = 'uploads/'.$company->id;
            if ($request->hasFile('file')) {
                $document = $request->file('file');
                $originalName = $document->getClientOriginalName();
                $fileName = 'company_document.' . $document->getClientOriginalExtension();
                //$filePath = $document->storeAs($folder, $fileName);
                $filePath = $document->move(public_path($folder), $fileName);
           
                $document = CompanyDocument::updateOrCreate([
                    'company_id'   => $company->id,
                ],[
                    'doc_type' => $doc->id,
                    'doc_name' => $originalName,
                    'doc_file' => $folder.'/'.$fileName,
                    'expiry_date' => $request->expiry_date
                ]);
            
                
                DB::commit();
                return redirect()->route('profile.index')->with('success', 'Password changed successfully.');
            }
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
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
    
    public function card(){
        return view('card');
    }
    
    public function companyProfile(){
        $company_id = auth()->user()->company_id;
        
        $user = auth()->user();
        $company = Company::with('document','payment')->find($company_id);
        return view('profile.company-profile',compact('user','company'));
    }
    
     public function updateCompanyProfilePic(Request $request){
        DB::beginTransaction();
        try{
            $user = auth()->user();
            $company = Company::find($user->company_id);
            $folder = 'uploads/'.$company->id;
            
             // Check if logo exists and delete it
            if (File::exists(public_path($company->logo))) {
                File::delete(public_path($company->logo));
            }
    
            if ($request->hasFile('logo_file')) {
                $logo = $request->file('logo_file');
                $originalName = $logo->getClientOriginalName();
                $fileName = 'company_logo.' . $logo->getClientOriginalExtension();
                //$filePath = $logo->storeAs($folder, $fileName);
                $filePath = $logo->move(public_path($folder), $fileName);
            }
            
            $company->update(['logo' => $folder.'/'.$fileName]);

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
    

}

