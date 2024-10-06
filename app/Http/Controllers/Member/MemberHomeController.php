<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\BidInboxListResource;
use App\Http\Resources\Member\EnquiryResource;
use App\Http\Resources\Member\EnquiryReplyResource;
use App\Http\Requests\Member\AnswerFaqRequest;
use App\Http\Requests\Member\ShareRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Enquiry;
use App\Models\EnquiryFaq;
use App\Models\EnquiryReply;
use App\Models\ReportedIssue;
use App\Models\Package;
use App\Models\PortalTodo;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class MemberHomeController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function index(Request $request, $ref = null, $reply_id = null){
        $user = auth()->user();
        if($user->token != ''){
            $user->update(['token'=>'']);
        }
        $company_id = auth()->user()->company_id;
        $members = CompanyUser::where(['company_id' => $company_id,'role' => 2])->get();
        $query = Enquiry::with('all_replies')->where('from_id',$user->id)->where('is_draft',0); //->where('is_closed',0)->verified()
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
        $enquiries = $query->notShared()->latest()->get();
        
        $selected_enquiry = null;
        $highlighted_reply_id = null;

        if ($enquiries->isNotEmpty()) {
            if($ref){
                $reference_no = Crypt::decryptString($ref);
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies')->where('reference_no',$reference_no)->first(); 
            }
            else{
                $ref = $enquiries->first()->reference_no;
                $selected_enquiry = Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies','shortlisted_replies')->where('reference_no',$ref)->first(); 
            }
        }

        if ($reply_id) {
            $highlighted_reply_id = (int)Crypt::decryptString($reply_id);
        }
        
        return view('member.inbox.index',compact('members','enquiries','selected_enquiry','highlighted_reply_id'));
    }

    public function fetchAllEnquiries(Request $request){
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
        $enquiries = $query->notShared()->latest()->get();  //->verified()
        
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

    public function skipFaq(Request $request){
        DB::beginTransaction();
        try{
            EnquiryFaq::findOrFail($request->id)->update([
                 'status' => 2
            ]);

            $faq = EnquiryFaq::findOrFail($request->id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Question has been skipped',
                'faq' => $faq
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function answerFaq(AnswerFaqRequest $request){
        DB::beginTransaction();
        try{
            EnquiryFaq::findOrFail($request->id)->update([
                'answer' => $request->answer,
                'answered_at' => now(),
                'status' => 1
            ]);

            $faq = EnquiryFaq::findOrFail($request->id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Question has been skipped',
                'faq' => $faq
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function readReply (Request $request){
        DB::beginTransaction();
        try{
            $reply = EnquiryReply::with('sender')->findOrFail($request->reply_id);
            
            if($reply->is_read == 0){
                $reply->update(['is_read' => 1]);
                
                $reply = EnquiryReply::with('sender','enquiry','relation')->findOrFail($request->reply_id);
            }
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Fetched Reply',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function shortlist (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'status' => 1
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Shortlisted Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function select (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'is_selected' => 1,
                'is_ignored'  => 0
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Selected Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function ignore (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'is_selected' => 0,
                'is_recommanded' => 0,
                'is_ignored'  => 1
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Ignored Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function unselect (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'is_selected' => 0
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Unselected Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function hold (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'status' => 2,
                'hold_reason' => $request->reason
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Shortlisted Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function approveInterest (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'participation_approved' => now()
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Approved Participation Interest!',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function report(Request $request){
        DB::beginTransaction();
        try{
            ReportedIssue::create([
                'category' => $request->category,
                'type' => $request->type,
                'enquiry_id' => $request->enquiry_id,
                'question_id' => $request->question_id,
                'reported_by' => auth()->user()->id,
                'reported_at' => now()
            ]);
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Reported Content',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function share(ShareRequest $request){
        
        // check subscription limit
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        $limited_quota = '-';
        if($package->id == 1){
            $limited_quota = $package->member_review_quote_limit;
            
            if($limited_quota != '-'){
               
                $currentMonthStartDate = now()->startOfMonth()->startOfDay();
                $today = now()->endOfDay();
                $count = Enquiry::where('from_id',auth()->user()->id)->whereNotNull('shared_to')->whereBetween('created_at', [$currentMonthStartDate, $today])->count();
                
                if($count >= $limited_quota){
                     return response()->json([
                        'status' => false,
                        'type' => 0,
                        'message' => 'Limit Exceeded, Upgrade to Standard Plan for unlimited usage.'
                    ], 500);
                }
            }
        }
        
        DB::beginTransaction();
        try{
            Enquiry::findOrFail($request->id)->update([
                 'shared_to' => $request->shared_to,
                 'share_priority' => $request->share_priority,
                 'shared_at' => now()
            ]);
            
             $enquiry = Enquiry::find($request->id);
             
             $encryptedRefNo = Crypt::encryptString($enquiry->reference_no); 
            
            PortalTodo::create([
                'company_id' => $enquiry->company_id,
                'user_id' => $request->shared_to,
                'type' => 'review_request',
                'enquiry_ref' => $enquiry->reference_no,
                'message' => 'Review Request Received',
                'action_url' => url('procurement/review-list/received/'.$encryptedRefNo) 
            ]);   

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Enquiry shared',
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

