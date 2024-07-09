<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Services\ProcurementService;
use App\Http\Resources\Procurement\BidInboxListResource;
use App\Http\Resources\Procurement\EnquiryResource;
use App\Http\Resources\Procurement\EnquiryReplyResource;
use App\Http\Requests\Procurement\{
        AnswerFaqRequest,
        HoldRequest,
        ShareRequest
    };
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
        Validator,
        Cache,
        Mail,
        Hash,
        Str,
        Crypt
    };
use App\Mail\OTP;
use App\Models\{
        Company,
        CompanyUser,
        Enquiry,
        EnquiryFaq,
        EnquiryReply,
        ReportedIssue,
        Package,
        PortalTodo
    };
use Carbon\Carbon;    
use DB;





class ProcurementHomeController extends Controller
{
    
    private $procurementService;
    
    public function __construct(ProcurementService $procurementService) {
        $this->procurementService = $procurementService;
    }
    
    public function index(Request $request,$ref = null){
        
        $user = auth()->user();
        if($user->token != ''){
            $user->update(['token'=>'']);
        }
        
        
        $query = Enquiry::select('id','reference_no','created_at','expired_at','subject')->with('all_replies')->verified()->where('from_id', $user->id)->where('is_draft', 0);
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
        
        $members = $this->procurementService->getCompanyMembers();
        
        return view('procurement.inbox.index',compact('enquiries','selected_enquiry','members'));
    }

    public function fetchAllEnquiries(Request $request){
        
        try {
            
            $enquiries = $this->procurementService->fetchAllEnquiries($request);
            
            return response()->json([
                'status' => true,
                'enquiries' => BidInboxListResource::collection($enquiries),
            ], 200);
            
        } catch (Exception $e) {
               Log::error($e->getMessage());

                return response()->json([
                    'status' => false,
                    'message' => 'Failed to fetch enquiries. Please try again later.',
                ], 500);
        }
    }

    public function fetchEnquiry(Request $request){
        
        try {
            
            $enquiry = $this->procurementService->fetchEnquiry($request->id);
            
            return response()->json([
                'status' => true,
                'enquiry' => new EnquiryResource($enquiry),
            ], 200);
            
        } catch (\Exception $e) {
            
            Log::error($e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch the enquiry. Please try again later.',
            ], 500);
            
        }
    }
    
    public function readReply (Request $request){
        
        try {
            
            $reply = $this->procurementService->readReply($request);

            return response()->json([
                'status' => true,
                'message' => 'Fetched Reply',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch bid details. Please try again later.',
            ], 500);
            
        }
        
    }
    
    public function approveInterest (Request $request){
         
        try {
            
            $reply = $this->procurementService->approveInterest($request);
            
            return response()->json([
                'status' => true,
                'message' => 'Approved Participation Interest!',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            Log::error($e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to approve participation interest. Please try again later.',
            ], 500);
            
        }
        
    }

    public function skipFaq(Request $request){
        
        try {
            
            $faq = $this->procurementService->skipFaq($request->id);

            return response()->json([
                'status' => true,
                'message' => 'Question has been skipped',
                'faq' => $faq
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to skip question. Please try again later.',
            ], 500);
            
        }
        
    }
    
    public function answerFaq(AnswerFaqRequest $request){
        
        try {
            
            $faq = $this->procurementService->answerFaq($request->id, $request->answer);

            return response()->json([
                'status' => true,
                'message' => 'Question has been answered',
                'faq' => $faq
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to respond. Please try again later.',
            ], 500);
            
        }
    
    }

    
    public function shortlist (Request $request){
        
        try {
            
            $reply = $this->procurementService->shortlist($request);

            return response()->json([
                'status' => true,
                'message' => 'Shortlisted Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to shortlist. Please try again later.',
            ], 500);
            
        }
        
    }
    
    public function hold (HoldRequest $request){
        
        try {
            
            $reply = $this->procurementService->hold($request);

            return response()->json([
                'status' => true,
                'message' => 'Bid set to hold',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to hold. Please try again later.',
            ], 500);
            
        }
        
    }

   

    public function select (Request $request){
        
        try {
            
            $reply = $this->procurementService->select($request);

            return response()->json([
                'status' => true,
                'message' => 'Selected Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to select bid. Please try again later.',
            ], 500);
            
        }
        
    }
    
     public function proceed (Request $request){
        
        try {
            
            $reply = $this->procurementService->proceed($request);

            return response()->json([
                'status' => true,
                'message' => 'Confirmed Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to confirm bid. Please try again later.',
            ], 500);
            
        }
        
    }

    public function unselect (Request $request){
        
        try {
            
            $reply = $this->procurementService->unselect($request);

            return response()->json([
                'status' => true,
                'message' => 'Unselected Bid',
                'reply' => new EnquiryReplyResource($reply)
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to un-select bid. Please try again later.',
            ], 500);
            
        }
        
    }

   

    

    public function report(Request $request){
        
        try {
            
            $this->procurementService->report($request);

            return response()->json([
                'status' => true,
                'message' => 'Reported Content',
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status'  => false,
                'message' => 'Failed to report content. Please try again later.',
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


    public function share(ShareRequest $request){
        
        $company_id = auth()->user()->company_id;
        
        // check subscription limit
        
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        $limited_quota = '-';
        if($package->id == 1){
            $limited_quota = $package->procurement_review_quote_limit;
        
       
            
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
                'action_url' => url('member/review-list/received/'.$encryptedRefNo) 
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

