<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Enquiry;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class MemberDraftController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function index($id = null){
        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $drafts = Enquiry::where(['company_id' => $company_id, 'from_id' =>$user_id, 'is_draft' => 1 ])->get();
        $selected_draft = null;
        if ($drafts->isNotEmpty()) {
            if($id){
                $selected_draft = Enquiry::with('sub_category','attachments','sender','sender.company')->find($id); 
            }
            else{
                $id = $drafts->first()->id;
                $selected_draft = Enquiry::with('sub_category','attachments','sender','sender.company')->find($id); 
            }
        }
        return view('member.draft.index',compact('drafts','selected_draft'));
    }

    public function openDraft(Request $request){
      DB::beginTransaction();
      try{
        $company_id = auth()->user()->company_id;
        $user_id = auth()->user()->id;
        $draft = Enquiry::with('sub_category','attachments','sender','sender.company')->find($request->id);

        DB::commit();
      
          return response()->json([
              'status' => true,
              'data' => $draft,
          ], 200);

        } catch (\Exception $e) {
          DB::rollback();
          return response()->json([
              'status' => false,
              'message' => $e->getMessage()
          ], 500);
      }         
    } 

    public function discardDraft (Request $request){
      DB::beginTransaction();
      try{
          $enquiry = Enquiry::findOrFail($request->id);
          $enquiry->attachments()->delete();
          $enquiry->delete();
          DB::commit();
           return redirect()->route('procurement.draft')->with('success','Discarded Draft!');
      } catch (\Exception $e) {
        DB::rollback();
        return redirect()->route('procurement.draft')->with('warning','Something went wrong!');
      }   

    }

}

