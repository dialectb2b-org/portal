<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Procurement\BidInboxListResource;
use App\Http\Resources\Procurement\EnquiryResource;
use App\Http\Requests\Procurement\AnswerFaqRequest;
use App\Http\Requests\Procurement\HoldRequest;
use App\Http\Requests\Procurement\ShareRequest;
use App\Http\Resources\Procurement\EnquiryReplyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Enquiry;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class ProCompletedBiddingController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }

    public function send(Request $request, $ref = null){
      $user = auth()->user();
      $company_id = auth()->user()->company_id;
      $query = Enquiry::with('all_replies')->verified()->where('from_id',$user->id)->where('is_draft',0); //->where('is_closed',0)
      if(!is_null($request->keyword)){
          $query->where('reference_no','like','%'.$request->keyword.'%');
          $query->orwhere('subject','like','%'.$request->keyword.'%');
      }
      if($request->mode_filter == 'today'){
          $query->whereDate('enquiries.created_at', Carbon::today());
      }
      else if($request->mode_filter == 'yesterday'){
          $query->whereDate('enquiries.created_at', Carbon::yesterday());
      }
      else if($request->mode_filter == 'this_week'){
          $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
      else if($request->mode_filter == 'last_week'){
        $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
      }
      else if($request->mode_filter == 'this_month'){
          $startOfMonth = now()->startOfMonth();
          $endOfMonth = now()->endOfMonth();
      
          $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      else if($request->mode_filter == 'last_month'){
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();
    
        $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      $enquiries = $query->shared()->completed()->latest()->get();
      
      $selected_enquiry = null;
        if ($enquiries->isNotEmpty()) {
            if($ref){
                $reference_no = Crypt::decryptString($ref);
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies','selected_replies','action_replies')->where('reference_no',$reference_no)->first(); 
            }
            else{
                $ref = $enquiries->first()->reference_no;
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies','selected_replies','action_replies')->where('reference_no',$ref)->first(); 
            }
        }
        
      return view('procurement.completedbidding.send.index',compact('enquiries','selected_enquiry'));
    }

    public function fetchAllSendEnquiries(Request $request){
      $user = auth()->user();
      $query = Enquiry::with('all_replies')->verified()->where('from_id',$user->id)->where('is_draft',0); //->where('is_closed',0)
      if(!is_null($request->keyword)){
          $query->where('reference_no','like','%'.$request->keyword.'%');
          $query->orwhere('subject','like','%'.$request->keyword.'%');
      }
      if($request->mode_filter == 'today'){
          $query->whereDate('enquiries.created_at', Carbon::today());
      }
      else if($request->mode_filter == 'yesterday'){
          $query->whereDate('enquiries.created_at', Carbon::yesterday());
      }
      else if($request->mode_filter == 'this_week'){
          $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
      else if($request->mode_filter == 'last_week'){
        $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
      }
      else if($request->mode_filter == 'this_month'){
          $startOfMonth = now()->startOfMonth();
          $endOfMonth = now()->endOfMonth();
      
          $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      else if($request->mode_filter == 'last_month'){
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();
    
        $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      $enquiries = $query->shared()->completed()->latest()->get();
      return response()->json([
          'status' => true,
          'enquiries' => BidInboxListResource::collection($enquiries),
      ], 200);
  }

  public function fetchEnquiry(Request $request){
    $enquiry = Enquiry::with('all_replies','sender','sender.company')->findOrFail($request->id);
    return response()->json([
        'status' => true,
        'enquiry' => new EnquiryResource($enquiry),
    ], 200);
}

    public function received(Request $request, $ref = null){
        $user = auth()->user();
        $company_id = auth()->user()->company_id;
        $query = Enquiry::with('all_replies')->verified()->where('shared_to',$user->id)->where('is_draft',0); //->where('is_closed',0)
        if(!is_null($request->keyword)){
            $query->where('reference_no','like','%'.$request->keyword.'%');
            $query->orwhere('subject','like','%'.$request->keyword.'%');
        }
        if($request->mode_filter == 'today'){
            $query->whereDate('enquiries.created_at', Carbon::today());
        }
        else if($request->mode_filter == 'yesterday'){
            $query->whereDate('enquiries.created_at', Carbon::yesterday());
        }
        else if($request->mode_filter == 'this_week'){
            $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
        else if($request->mode_filter == 'last_week'){
            $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
        }
        else if($request->mode_filter == 'this_month'){
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
      
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }
        else if($request->mode_filter == 'last_month'){
            $startOfMonth = now()->subMonth()->startOfMonth();
            $endOfMonth = now()->subMonth()->endOfMonth();
    
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }
        $enquiries = $query->shared()->completed()->latest()->get();
        
        $selected_enquiry = null;
        if ($enquiries->isNotEmpty()) {
            if($ref){
                $reference_no = Crypt::decryptString($ref);
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies','selected_replies','action_replies')->where('reference_no',$reference_no)->first(); 
            }
            else{
                $ref = $enquiries->first()->reference_no;
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies','selected_replies','action_replies')->where('reference_no',$ref)->first(); 
            }
        }
        return view('procurement.completedbidding.received.index',compact('enquiries','selected_enquiry'));
    }

    public function fetchAllReceivedEnquiries(Request $request){
      $user = auth()->user();
      $query = Enquiry::with('all_replies')->verified()->where('shared_to',$user->id)->where('is_draft',0); //->where('is_closed',0)
      if(!is_null($request->keyword)){
          $query->where('reference_no','like','%'.$request->keyword.'%');
          $query->orwhere('subject','like','%'.$request->keyword.'%');
      }
      if($request->mode_filter == 'today'){
          $query->whereDate('enquiries.created_at', Carbon::today());
      }
      else if($request->mode_filter == 'yesterday'){
          $query->whereDate('enquiries.created_at', Carbon::yesterday());
      }
      else if($request->mode_filter == 'this_week'){
          $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
      else if($request->mode_filter == 'last_week'){
        $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
      }
      else if($request->mode_filter == 'this_month'){
          $startOfMonth = now()->startOfMonth();
          $endOfMonth = now()->endOfMonth();
      
          $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      else if($request->mode_filter == 'last_month'){
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();
    
        $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
      }
      $enquiries = $query->shared()->completed()->latest()->get();
      return response()->json([
          'status' => true,
          'enquiries' => BidInboxListResource::collection($enquiries),
      ], 200);
  }
  
   public function markAsUnCompleted(Request $request){
        DB::beginTransaction();
        try{
            Enquiry::findOrFail($request->id)->update(['is_completed' => 0]);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Marked as uncompleted',
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

