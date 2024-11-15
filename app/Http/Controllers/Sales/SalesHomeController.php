<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\{
        SendBidRequest,
        AskFaqRequest,
        AttachmentUploadRequest
    };
use App\Http\Resources\Sales\{
        ReceivedListResource,
        EnquiryResource
    };
use App\Mail\OTP;
use App\Models\{
        Company,
        CompanyUser,
        EnquiryRelation,
        EnquiryReply,
        EnquiryFaq,
        Enquiry,
        EnquiryAttachment,
        ReportedIssue,
        Package,
        Notification,
        PortalTodo
    };
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
        Validator,
        Cache,
        Mail,
        Hash,
        Crypt
    };
use Illuminate\Support\Str;


class SalesHomeController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function index(Request $request, $id = null){
        $user = auth()->user();
        if($user->token != ''){
            $user->update(['token'=>'']);
        }
        
        $company_id = auth()->user()->company_id;
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        
        $query = EnquiryRelation::with([
            'enquiry',
            'enquiry.sub_category',
            'enquiry.sender.company',
            'enquiry.all_faqs',
            'enquiry.my_faqs'
        ])->where('to_id', $user->id)
        ->join('enquiries', 'enquiries.id', '=', 'enquiry_relations.enquiry_id')
        ->orderBy('enquiries.created_at', 'desc');

        $query->whereHas('enquiry', function ($query) use ($request) {
            $query->whereNull('enquiries.shared_to')
                  ->where('enquiries.is_draft', 0);

            if (!is_null($request->keyword)) {
                $query->where(function ($query) use ($request) {
                    $query->where('enquiries.reference_no', 'like', '%' . $request->keyword . '%')
                          ->orWhere('enquiries.subject', 'like', '%' . $request->keyword . '%');
                });
            }
    
            if ($request->mode_filter) {
                switch ($request->mode_filter) {
                    case 'newest_on_top':
                        $query->orderBy('enquiry_relations.created_at', 'desc');
                        break;
                    case 'oldest_on_top':
                        $query->orderBy('enquiry_relations.created_at', 'asc');
                        break;
                    case 'near_expiry':
                        $query->orderBy('enquiries.expired_at', 'desc');
                        break;
                    // case 'today':
                    //     $query->whereDate('enquiries.created_at', '=', now()->toDateString());
                    //     break;
                    // case 'yesterday':
                    //     $query->whereDate('enquiries.created_at', '=', now()->subDay()->toDateString());
                    //     break;
                    // case 'this_week':
                    //     $query->whereBetween('enquiries.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    //     break;
                    // case 'last_week':
                    //     $query->whereBetween('enquiries.created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    //     break;
                    // case 'this_month':
                    //     $query->whereYear('enquiries.created_at', now()->year)
                    //           ->whereMonth('enquiries.created_at', now()->month);
                    //     break;
                    // case 'last_month':
                    //     $query->whereYear('enquiries.created_at', now()->subMonth()->year)
                    //           ->whereMonth('enquiries.created_at', now()->subMonth()->month);
                    //     break;
                }
            }
        });

        // $query->orderBy('enquiries.created_at', 'desc');

        $enquiries = $query->notExpired()->notReplied()->get();
        
        $selected_enquiry = null;
        if ($enquiries->isNotEmpty()) {
            if($id){
                 $id = Crypt::decryptString($id);  
                $selected_enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company')->findOrFail($id);
            }
            else{
                $id = $enquiries->first()->id;
                $selected_enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company')->findOrFail($id); 
            }
        }

        return view('sales.received.index',compact('package','enquiries','selected_enquiry'));
    }

    public function fetchAllEnquiries(Request $request){
        $user = auth()->user();
        // $query = EnquiryRelation::with('enquiry','enquiry.sub_category','enquiry.sender.company','enquiry.all_faqs','enquiry.my_faqs')->where('to_id',$user->id); //->where('is_closed',0)
        // $query->whereHas('enquiry', function ($query) use ($request){
        //     $query->whereNull('enquiries.shared_to');
        //     $query->where('enquiries.is_draft',0);
        //     if(!is_null($request->keyword)){
        //         $query->where('enquiries.reference_no','like','%'.$request->keyword.'%');
        //         $query->orwhere('enquiries.subject','like','%'.$request->keyword.'%');
        //     }
        //     if($request->mode_filter == 'today'){
        //         $query->whereDate('enquiries.created_at', Carbon::today());
        //     }
        //     else if($request->mode_filter == 'yesterday'){
        //         $query->whereDate('enquiries.created_at', Carbon::yesterday());
        //     }
        //     else if($request->mode_filter == 'this_week'){
        //         $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        //     }
        //     else if($request->mode_filter == 'last_week'){
        //         $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
        //     }
        //     else if($request->mode_filter == 'this_month'){
        //         $startOfMonth = now()->startOfMonth();
        //         $endOfMonth = now()->endOfMonth();
            
        //         $query->whereBetween('enquiries.created_at', [$startOfMonth, $endOfMonth]);
        //     }
        //     else if($request->mode_filter == 'last_month'){
        //         $startOfMonth = now()->subMonth()->startOfMonth();
        //         $endOfMonth = now()->subMonth()->endOfMonth();
            
        //         $query->whereBetween('enquiries.created_at', [$startOfMonth, $endOfMonth]);
        //     }
        //     else{
        //         $query->orderBy('enquiries.created_at','desc');
        //     }
            
            
        // })->groupBy('enquiry_id');
        // $enquiries = $query->notExpired()->notReplied()->get();
        
        $query = EnquiryRelation::with([
            'enquiry',
            'enquiry.sub_category',
            'enquiry.sender.company',
            'enquiry.all_faqs',
            'enquiry.my_faqs'
        ])->where('to_id', $user->id)
        ->join('enquiries', 'enquiries.id', '=', 'enquiry_relations.enquiry_id');

$query->whereHas('enquiry', function ($query) use ($request) {
    $query->whereNull('enquiries.shared_to')
          ->where('enquiries.is_draft', 0);

    if (!is_null($request->keyword)) {
        $query->where(function ($query) use ($request) {
            $query->where('enquiries.reference_no', 'like', '%' . $request->keyword . '%')
                  ->orWhere('enquiries.subject', 'like', '%' . $request->keyword . '%');
        });
    }

    if ($request->mode_filter) {
        switch ($request->mode_filter) {
            case 'newest_on_top':
                // $query->whereDate('enquiries.created_at', '=', now()->toDateString());
                $query->orderBy('enquiry_relations.created_at', 'desc');
                break;
            case 'oldest_on_top':
                // $query->whereDate('enquiries.created_at', '=', now()->subDay()->toDateString());
                $query->orderBy('enquiry_relations.created_at', 'asc');
                break;
            case 'near_expiry':
                // $query->whereBetween('enquiries.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $query->orderBy('enquiries.expired_at', 'asc');
                break;
            // case 'last_week':
            //     $query->whereBetween('enquiries.created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            //     break;
            // case 'this_month':
            //     $query->whereYear('enquiries.created_at', now()->year)
            //           ->whereMonth('enquiries.created_at', now()->month);
            //     break;
            // case 'last_month':
            //     $query->whereYear('enquiries.created_at', now()->subMonth()->year)
            //           ->whereMonth('enquiries.created_at', now()->subMonth()->month);
            //     break;
        }
    }
});

$enquiries = $query->notExpired()->notReplied()->get();

        
        return response()->json([
            'status' => true,
            'enquiries' => ReceivedListResource::collection($enquiries),
        ], 200);
    }

    public function fetchEnquiry(Request $request){
      $enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company')->findOrFail($request->id);
      return response()->json([
          'status' => true,
          'enquiry' => new EnquiryResource($enquiry),
      ], 200);
  }

  public function saveQuestion(AskFaqRequest $request){
    DB::beginTransaction();
        try{
            $enquiry = Enquiry::findOrFail($request->enquiry_id);
            $faq = EnquiryFaq::create([
                'enquiry_id' => $request->enquiry_id,
                'reference_no' => $enquiry->reference_no,
                'question' => $request->question,
                'created_by' => auth()->user()->id,
                'status' => 0
            ]);

            $enquiryRelation = EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])->first();
            
            Notification::create([
                'company_id' => $enquiry->company_id, 
                'user_id' => $enquiry->from_id,
                'type' => 1,
                'role' => 2,
                'title' => 'New Question against RFQ -'.$enquiry->reference_no,
                'message' => 'New Question against RFQ -'.$enquiry->reference_no,
                'action' => '', 
                'action_url' => '' 
                ]);
                
            $encryptedRefNo = Crypt::encryptString($enquiry->reference_no);  
            if($enquiry->sender_type == 2){
                $action_url = url('procurement/dashboard/'.$encryptedRefNo);
            }
            else{
                $action_url = url('member/dashboard/'.$encryptedRefNo);
            }
                
            PortalTodo::create([
                'company_id' => $enquiry->company_id,
                'user_id' => $enquiry->from_id,
                'type' => 'question',
                'enquiry_ref' => $enquiry->reference_no,
                'message' => 'Question Received',
                'action_url' =>  $action_url
            ]);        

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Question has been send',
                'enquiry' => $enquiryRelation
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
  }

    public function sendBid(SendBidRequest $request){
        
        $company_id = auth()->user()->company_id;
        
        // check subscription limit
        
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        $limited_quota = '-';
        if($package->id == 1){
            $limited_quota = $package->sales_respond_enquiry_limit;
            
            if($limited_quota != '-'){
               
                $currentMonthStartDate = now()->startOfMonth()->startOfDay();
                $today = now()->endOfDay();
                $count = Enquiry::where('enquiry_relations.is_replied',1)
                                  ->join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                  ->join('enquiry_replies','enquiry_replies.enquiry_id','enquiries.id')
                                  ->whereDate('enquiry_relations.updated_at', $today)
                                  ->where('enquiry_replies.from_id',auth()->user()->id)
                                  ->where('enquiry_replies.type',2)
                                  ->count();
             
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
            $enquiry = Enquiry::findOrFail($request->enquiry_id);
            $relation = EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])->first();
            $reply = EnquiryReply::updateOrCreate(
                [
                    'enquiry_id' => $enquiry->id,
                    'from_id' => auth()->user()->id,
                ],
                [	
                    'enquiry_relation_id' => $relation->id,
                    'body' => $request->body,	
                    'type' => 2,	
                    'status' => 0
                ]
            );
            EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])
                             ->update(['is_replied' => 1]);
                             
            Notification::create([
                'company_id' => $enquiry->company_id, 
                'user_id' => $enquiry->from_id,
                'type' => 1,
                'role' => 2,
                'title' => 'New Bid against RFQ -'.$enquiry->reference_no,
                'message' => 'New Bid against RFQ -'.$enquiry->reference_no,
                'action' => '', 
                'action_url' => '' 
                ]);
                
            $encryptedRefNo = Crypt::encryptString($enquiry->reference_no); 
            $encryptedReplyId = Crypt::encryptString((string)$reply->id);
           
            if($enquiry->sender_type == 2) {
                $action_url = url('procurement/dashboard/'.$encryptedRefNo.'/'.$encryptedReplyId);
            } else {
                $action_url = url('member/dashboard/'.$encryptedRefNo.'/'.$encryptedReplyId);
            }
                
            PortalTodo::create([
                'company_id' => $enquiry->company_id,
                'user_id' => $enquiry->from_id,
                'type' => 'proposal',
                'enquiry_ref' => $enquiry->reference_no,
                'message' => 'Proposal Received',
                'action_url' => $action_url 
            ]);    
            
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Bid Send!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendInterest(Request $request){
        
          $company_id = auth()->user()->company_id;
        
        // check subscription limit
        
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        $limited_quota = '-';
        if($package->id == 1){
            $limited_quota = $package->sales_limited_enquiry_participation_limit;
            
            if($limited_quota != '-'){
               
                $currentMonthStartDate = now()->startOfMonth()->startOfDay();
                $today = now()->endOfDay();
                $count = Enquiry::where('is_limited',1)
                                  ->where('enquiry_relations.to_id',auth()->user()->id)
                                  ->where('enquiry_relations.is_interested',1)
                                  ->join('enquiry_relations','enquiry_relations.enquiry_id','enquiries.id')
                                  ->whereBetween('enquiry_relations.updated_at', [$currentMonthStartDate, $today])
                                  ->count();
                
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
            $enquiry = Enquiry::findOrFail($request->enquiry_id);
            $relation = EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])->first();
            $reply = EnquiryReply::updateOrCreate(
                [
                    'enquiry_id' => $enquiry->id,
                    'from_id' => auth()->user()->id,
                ],
                [	
                    'enquiry_relation_id' => $relation->id,
                    'body' => '',	
                    'type' => 3,	
                    'status' => 0,
                    'is_interested' => now()
                ]
            );
             EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])
                             ->update(['is_replied' => 3, 'is_interested' => 1]);
                             
            $encryptedRefNo = Crypt::encryptString($enquiry->reference_no);  
            
            if($enquiry->sender_type == 2){
                $action_url = url('procurement/dashboard/'.$encryptedRefNo);
            }
            else{
                $action_url = url('member/dashboard/'.$encryptedRefNo);
            }
                             
            PortalTodo::create([
                'company_id' => $enquiry->company_id,
                'user_id' => $enquiry->from_id,
                'type' => 'particiation_request',
                'enquiry_ref' => $enquiry->reference_no,
                'message' => 'Participation Request',
                'action_url' => $action_url 
            ]);                    

            
            DB::commit();
            return response()->json([
                'status' => true,
                'enquiry' => $relation,
                'message' => 'Interest Send!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadAttachment(AttachmentUploadRequest $request){
    
        DB::beginTransaction();
        try{
            $company_id = auth()->user()->company_id;
            $enquiry_id = $request->enquiry_id;
            $reply_id = $request->reply_id;
            $enquiry = Enquiry::findOrFail($enquiry_id);
            $folder = 'uploads/'.$company_id.'/attchments/'.$enquiry_id;
            if ($request->hasFile('document_file')) {
                $document = $request->file('document_file');
                $originalName = $document->getClientOriginalName();
                $extension = $document->getClientOriginalExtension();
                $fileName = 'attchment.'.$enquiry_id.rand(0000,9999).$extension;
                //$filePath = $document->storeAs($folder, $fileName);
                $filePath = $document->move(public_path($folder), $fileName);
            }

            $relation = EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])->first();
            $reply = EnquiryReply::updateOrCreate(
                [
                    'enquiry_id' => $enquiry->id,
                    'from_id' => auth()->user()->id,
                ],	
                [
                    'enquiry_relation_id' => $relation->id,
                    'body' => $request->body,	
                    'type' => 1, //draft 
                    'status' => 0
                ]
            );

            EnquiryAttachment::create([
                'enquiry_id' =>	$enquiry_id,
                'reply_id' => $reply->id,
                'type' =>	$extension,
                'org_file_name' => $originalName,
                'file_name' =>	$fileName,
                'path' => $folder.'/'.$fileName
            ]);

            EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])
                             ->update(['is_replied' => 2]); //draft

            DB::commit();
             
            return response()->json([
                'status' => true,
                'enquiry_id' => $enquiry_id,
                'reply_id' => $reply->id,
                'data' => $document,
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

      public function getEnquiryAttachments(Request $request){
        $attachments = EnquiryAttachment::where('enquiry_id',$request->enquiry_id)->where('reply_id',$request->reply_id)->get();
        return response()->json([
            'status' => true,
            'attachments' => $attachments
        ], 200);
    }

    public function deleteAttachments(Request $request){
        DB::beginTransaction();
        try{

            $enquiryAttachment = EnquiryAttachment::where('id',$request->id)->first();
            $enquiryAttachment->delete();
            DB::commit();
             
            return response()->json([
                'status' => true,
                'attchment' => $enquiryAttachment,
                'message' => 'Deleted!',
            ], 200);
  
          } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
      }

    public function discard(Request $request){
        DB::beginTransaction();
        try{

           
            $reply = EnquiryReply::where(['enquiry_id' => $request->id,'from_id' => auth()->user()->id])->first();
        
            EnquiryAttachment::where([
                'enquiry_id' =>	$request->id,
                'reply_id' => $reply->id,
            ])->delete();
             
            $reply->delete();

            EnquiryRelation::where(['enquiry_id' => $request->id, 'to_id' => auth()->user()->id])
                             ->update(['is_replied' => 0]); 

            DB::commit();
             
            return response()->json([
                'status' => true,
                'message' => 'Discarded!',
            ], 200);
  
          } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveDraft(SendBidRequest $request){
        DB::beginTransaction();
        try{
            $enquiry = Enquiry::findOrFail($request->enquiry_id);
            $relation = EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])->first();
            $reply = EnquiryReply::updateOrCreate(
                [
                    'enquiry_id' => $enquiry->id,
                    'from_id' => auth()->user()->id,
                ],
                [	
                    'enquiry_relation_id' => $relation->id,
                    'body' => $request->body,	
                    'type' => 1, //draft 
                    'status' => 0
                ]
            );

            EnquiryRelation::where(['enquiry_id' => $enquiry->id, 'to_id' => auth()->user()->id])
                             ->update(['is_replied' => 2]); //draft
           
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Bid Send!'
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
    

}

