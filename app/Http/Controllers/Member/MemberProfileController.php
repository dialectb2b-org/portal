<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Country;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyDocument;
use App\Models\Notification;
use App\Models\Package;
use App\Models\PortalTodo;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class MemberProfileController extends Controller
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
        return view('member.profile.index',compact('user','company','package'));
    }

    public function edit(){
        $company_id = auth()->user()->company_id;
        $user = auth()->user();
        $company = Company::with('document')->find($company_id);
        $country = Country::where('id', $company->country_id)->first();
        return view('member.profile.edit',compact('user','company','country'));
    }

    public function update(Request $request,$id){
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
                                'email' => $request->email,
                            ]);
            DB::commit();                
            return redirect()->route('member.dashboard')->with('success','updated!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('member.dashboard')->with('error','Something went wrong!');
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
    
    public function notification(){
        $notifications = Notification::where('type',1)->where('user_id',auth()->user()->id)->get();
        $announcements = Notification::where('type',2)->where('user_id',auth()->user()->id)->get();
        return response()->json([
                'status' => true,
                'notifications' => $notifications,
                'announcements' => $announcements
            ], 200);
    }
    
    public function todo(){
        $todos = PortalTodo::where('user_id',auth()->user()->id)->get();
        return response()->json([
                'status' => true,
                'todos' => $todos
            ], 200);
    }

}

