<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator,
    Crypt,
    Cache,
    Mail
};
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Category;
use App\Models\Country;
use App\Models\SubCategory;
use App\Models\CompanyActivity;
use App\Models\Enquiry;
use App\Models\EnquiryAttachment;
use App\Models\EnquiryRelation;
use App\Models\RelativeSubCategory;
use App\Models\Package;
use App\Models\PortalTodo;
use App\Http\Requests\Member\NewQuoteRequest;
use App\Http\Requests\Member\DraftRequest;
use App\Http\Requests\Member\AttachmentUploadRequest;
use App\Http\Requests\Member\TimeFrameRequest;
use Carbon\Carbon;
use DB;
use Auth;


 
class MemberQuoteController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function selectCategory(){
        $company_id = auth()->user()->company_id;
        $categories = Category::all();
        return view('member.quote.select-category',compact('categories'));
    }

    public function changeCategory($id){
        $company_id = auth()->user()->company_id;
        $categories = Category::all();
        $enquiry = Enquiry::with('sub_category')->find($id);
        return view('member.quote.change-category',compact('categories','enquiry'));
    }

    public function saveCategory(Request $request){
        DB::beginTransaction();
        try{
            $company_id = auth()->user()->company_id;
            $enquiry = Enquiry::create([
                'company_id' => $company_id,
                'sub_category_id' => $request->id,
                'from_id' => auth()->user()->id
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $enquiry
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function updateCategory(Request $request){
        DB::beginTransaction();
        try{
            $company_id = auth()->user()->company_id;
            $ref_no = $this->referenceNo($company_id);
            Enquiry::findOrFail($request->enquiry_id)->update([
                'sub_category_id' => $request->id
            ]);

            $enquiry = Enquiry::findOrFail($request->enquiry_id);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $enquiry
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function compose($id){
      if(!$id){
          return redirect()->route('member.quote.selectCategory')->with('warning','Please choose a service');
      }

      $company_id = auth()->user()->company_id;
      $countries = Country::where('status',1)->get();
      $enquiry = Enquiry::with('sub_category')->find($id);
      return view('member.quote.compose',compact('enquiry','countries'));
    }

    public function uploadAttachment(AttachmentUploadRequest $request){
      DB::beginTransaction();
      try{
          $company_id = auth()->user()->company_id;
          $enquiry_id = $request->enquiry_id;
          $folder = 'uploads/'.$company_id.'/attchments/'.$enquiry_id;
          if ($request->hasFile('document_file')) {
              $document = $request->file('document_file');
              $originalName = $document->getClientOriginalName();
              $extension = $document->getClientOriginalExtension();
              $fileName = 'attchment.'.$enquiry_id.rand(0000,9999).$extension;
              //$filePath = $document->storeAs($folder, $fileName);
              $filePath = $document->move(public_path($folder), $fileName);
          }
          EnquiryAttachment::create([
              'enquiry_id' =>	$enquiry_id,
              'type' =>	$extension,
              'file_name' =>	$fileName,
              'path' => $folder.'/'.$fileName
          ]);
          DB::commit();
           
          return response()->json([
              'status' => true,
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
        $attachments = EnquiryAttachment::where('enquiry_id',$request->enquiry_id)->get();
        return response()->json([
            'status' => true,
            'attachments' => $attachments
        ], 200);
    }

    public function deleteAttachments(Request $request){
      DB::beginTransaction();
      try{
          EnquiryAttachment::where('id',$request->id)->delete();
          DB::commit();
           
          return response()->json([
              'status' => true,
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
    
    public function saveAsDraft(DraftRequest $request){
      DB::beginTransaction();
      try{
        Enquiry::find($request->enquiry_id)
                ->update([
                    'subject' => $request->subject,
                    'body' => $request->body,
                    'is_draft' => 1,
                    'is_limited' => $request->is_limited == 1 ? 1 : 0,
                    'country_id' => $request->country_id,
                    'region_id' => $request->region_id,
                    'expired_at' => Carbon::parse($request->expired_at)->format('Y-m-d')
                ]);
          DB::commit();
      
          return response()->json([
              'status' => true,
              'message' => 'Saved as draft!',
          ], 200);

        } catch (\Exception $e) {
          DB::rollback();
          return response()->json([
              'status' => false,
              'message' => $e->getMessage()
          ], 500);
      }         
    }

    public function generateQuote(NewQuoteRequest $request){
        $company_id = auth()->user()->company_id;
        
         // check subscription limit
        
        $company = Company::find($company_id);
        $package = Package::find($company->current_plan);
        $limited_quota = '-';
        if($package->id == 1){
            
            if($request->is_limited == 1){
                $limited_quota = $package->member_limited_enquiry_limit;
            }
       
            
            if($limited_quota != '-'){
               
                $currentMonthStartDate = now()->startOfMonth()->startOfDay();
                $today = now()->endOfDay();
                $count = Enquiry::where('from_id',auth()->user()->id)->where('is_draft',0)->whereBetween('created_at', [$currentMonthStartDate, $today])->count();
                if($count >= $limited_quota){
                     return response()->json([
                        'status' => false,
                        'type' => 0,
                        'message' => 'Limit Exceeded, Upgrade to Standard Plan for unlimited usage.'
                    ], 402);
                }
            }
        }
        
        
        $ref_no = $this->referenceNo($company_id);
        $user = auth()->user();
        DB::beginTransaction();
        try{
            $enquiry = Enquiry::find($request->enquiry_id);
            $enquiry->update([
                    'reference_no' => $ref_no,
                    'subject' => $request->subject,
                    'body' => $request->body,
                    'is_draft' => 0,
                    'is_limited' => $request->is_limited == 1 ? 1 : 0,
                    'country_id' => $request->country_id,
                    'region_id' => $request->region_id,
                    'expired_at' => Carbon::parse($request->expired_at)->format('Y-m-d'),
                    'mail_type' => 1,
                    'sender_type' => auth()->user()->role,
                    'approve_status' => 0,
                    'verified_by' => $user->approval_status == 1 ? $user->id : 0, 
                    'verified_at' => $user->approval_status == 1 ? now() : null
            ]);

            if($user->approval_status == 1){

                $recipients = $this->fetchRecipients($enquiry->sub_category_id, $request->country_id, $request->region_id);

                $recipient_chunks = array_chunk($recipients, 100); // Split records into chunks for batch processing

                foreach ($recipient_chunks as $recipient) {
                    foreach($recipient as $res){
                        EnquiryRelation::insert([
                            'enquiry_id' => $enquiry->id,
                            'recipient_company_id' => $res['company_id'],
                            'to_id' =>	$res['id'],
                            'is_read' => 0,	
                            'is_replied' => 0
                        ]);
                    }
                }
            }
            else{
                $procurement = CompanyUser::where(['company_id' => $company->id, 'role' => 2])->first();
            
                $details = [
                    'subject'	=>'Enquiry Approval Request: Action Required.',
                    'salutation' => '<p style="text-align: left;">Dear '.$procurement->name ?? 'User,</p>',
                    'introduction' => "<p>We hope you're having a great day.</p>",
                    'body' => "<p>You are receiving this email because a new enquiry awaits your approval<br>for publishing on Dialectb2b.com Your review and decision are crucial<br>to the next steps in the publication process.<br>Please log in to your account on Dialectb2b.com to review the enquiry<br>details and provide your approval.</p>",
                    'closing' => "<p>Your prompt attention to this matter is greatly appreciated</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
                \Mail::to($procurement->email)->send(new \App\Mail\CommonMail($details));
                
                $encryptedRefNo = Crypt::encryptString($enquiry->reference_no); 
                
                PortalTodo::create([
                    'company_id' => $enquiry->company_id,
                    'user_id' => $procurement->id,
                    'type' => 'waiting_permission',
                    'enquiry_ref' => $enquiry->reference_no,
                    'message' => 'Waiting Permission',
                    'action_url' => url('procurement/team-account/approval/'.$encryptedRefNo) 
                ]);    
            }

            DB::commit();
      
            return response()->json([
                'status' => true,
                'enquiry_id' => $enquiry->id,
                'message' => '<div class="col-md-12 common-popup">
                                <h1 class="quote-generated-title text-center">Quote Generated</h1>
                                <div class="quote-popup-content">
                                    Quote generated successfully.<br>
                                    Your Reference ID is <span>'.$ref_no.'</span>
                                </div>
                            </div>',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function referenceNo($company_id){
        $enquiryCount = 0;
        $company = Company::with('document')->find($company_id);
        $doc = $company->document->doc_number;
        $year = date('Y');
        $enquiryCount = Enquiry::where('company_id',$company->id)->where('from_id',auth()->user()->id)->whereYear('created_at', '=', $year)->distinct()->count('reference_no') + 1;
        return 'G-'.$doc.'-'.$enquiryCount.'-'.$year;
    }

    public function fetchRecipients($sub_category_id, $country_id, $region_id){
        $role = 3; // sales
        $categories = RelativeSubCategory::where('sub_category_id',$sub_category_id)->pluck('relative_id');
        $query = Company::where('company_users.role', '=', $role)
                        ->where('company_users.company_id','!=',auth()->user()->company_id)
                        ->where('companies.country_id', $country_id);
                        if($categories->count() > 0){
                            $categories = $categories->prepend($sub_category_id)->toArray();
                            $query->whereIn('company_activities.activity_id',$categories);
                        }
                        else{
                            $query->where('company_activities.activity_id',$sub_category_id);
                        }
                        $query->select( 'company_users.company_id', 'company_users.email', 'company_users.id')
                        ->join('company_users', 'company_users.company_id', '=', 'companies.id')
                        ->join('company_activities', 'company_activities.company_id', '=', 'companies.id');                    
        if($region_id != 0){
            $query->join('company_locations', 'company_locations.company_id', '=', 'companies.id')
                  ->where('company_locations.region_id',$region_id);
        }                 
        $data = $query->get(); 
        
        return $recipients = $data->toArray();
    }
    
     public function undoGenerateQuote(Request $request){
        DB::beginTransaction();
        try{
            $enquiry_id = $request->enquiry_id;
            Enquiry::find($enquiry_id)->delete();
            EnquiryRelation::where('enquiry_id',$enquiry_id)->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' =>'Undo Send'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function editAcceptedDate(TimeFrameRequest $request){
        DB::beginTransaction();
        try{
            $company_id = auth()->user()->company_id;
            Enquiry::findOrFail($request->id)->update([
                'expired_at' => Carbon::parse($request->expire_at)->format('Y-m-d')
            ]);

            $enquiry = Enquiry::findOrFail($request->id);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $enquiry
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

