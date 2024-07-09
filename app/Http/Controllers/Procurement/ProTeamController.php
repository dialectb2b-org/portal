<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Procurement\NewMemberRequest;
use App\Http\Resources\Procurement\BidInboxListResource;
use App\Http\Resources\Procurement\EnquiryResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Enquiry;
use App\Models\EnquiryRelation;
use App\Models\RelativeSubCategory;
use DB;
use Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SendGridEmailService;
use Illuminate\Support\Facades\Crypt;


class ProTeamController extends Controller
{
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->middleware('auth');
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function approval(Request $request, $ref = null){
        $company_id = auth()->user()->company_id;
        $query = Enquiry::select('id','reference_no','subject','created_at','expired_at')
            ->where('company_id',$company_id)->where('verified_by',0)->where('is_draft',0); //->where('is_closed',0)
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
        $enquiries = $query->latest()->get();
        $selected_enquiry = null;
        if($ref){
            $reference_no = Crypt::decryptString($ref);
            $selected_enquiry = Enquiry::with('sender','attachments')->where('reference_no',$reference_no)->first();
        }
        else{
            if ($enquiries->isNotEmpty()) {
                $id = $enquiries->first()->id;
                $selected_enquiry = Enquiry::with('sender','attachments')->find($id);
            }
        }
       
        return view('procurement.approval.index',compact('enquiries','selected_enquiry'));
    }

    public function fetchAllApprovalEnquiries(Request $request){
        $user = auth()->user();
        $company_id = auth()->user()->company_id;
        $query = Enquiry::where('company_id',$company_id)->where('verified_by',0)->where('is_draft',0); //->where('is_closed',0)
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
        $enquiries = $query->latest()->get();
        return response()->json([ 
            'status' => true,
            'enquiries' => BidInboxListResource::collection($enquiries),
        ], 200);
    }

    public function approveQuote(Request $request){
        DB::beginTransaction();
        try{
            $company_id = auth()->user()->company_id;
            Enquiry::findOrFail($request->id)->update([
                'verified_by' => auth()->user()->id,
                'verified_at' => now()
            ]);

            $enquiry = Enquiry::findOrFail($request->id);

            $recipients = $this->fetchRecipients($enquiry->sub_category_id, $enquiry->country_id, $enquiry->region_id);

            $recipient_chunks = array_chunk($recipients, 100); 

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

            DB::commit();

            $teammember = CompanyUser::find($enquiry->from_id);
          
            
            $details = [
                'subject'	=>'Enquiry Approval Received.',
                'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$teammember->name ?? 'User,</p>',
                'introduction' => "<p>We hope this message finds you well.</p>",
                'body' => "<p>We hope this message finds you well.<br>Congratulations! We are pleased to inform you that your recent enquiry<br> on dialectb2b.com has been approved by your procurement team.Your enquiry is now published.</p>",
                'closing' => "<p> We appreciate  your contribution to our platform<br>and look forward to showcasing your content.<br>To view the confirmed publication details please log into your account<br> on dialectb2b.com <a href=''></a>.<br>Thank you for choosing Dialectb2b.com for your business needs. We're<br> excited to share your enquiry with our audience.</p>",
                'otp' => null,
                'link' => null,
                'link_text' => null,
                "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
            ];
            
            $subject	= 'Enquiry Approval Received.';
            $htmlBody = view('email.common',compact('details'))->render();
                
            //$result = $this->sendGridEmailService->send($teammember->email, $subject, $htmlBody, true);
                
            \Mail::to($teammember->email)->send(new \App\Mail\CommonMail($details));

            return response()->json([
                'status' => true,
                'data' => $enquiry,
                'message' => 'Quote Approved!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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


    public function team(){
       $company_id = auth()->user()->company_id;
       $members = CompanyUser::where(['company_id' => $company_id,'role' => 4])->get();
       return view('procurement.team.index',compact('members'));
    }

    public function saveMember(NewMemberRequest $request){
      DB::beginTransaction();
        try{
            
            $company = Company::find(auth()->user()->company_id);
            
            if($company->domain){
                $allowed_domains = array($company->domain);
                $parts = explode('@', $request->email);
                $domain = array_pop($parts);
                if (in_array($domain, $allowed_domains)){
                    return response()->json([
                        'status' => false,
                        'type' => 'companydomain',
                        'message' => 'Domain User can create his own Team User Account directly', 
                    ], 412);
                }
            }

            $plaintext = Str::random(32);

            $member = CompanyUser::create([
              'company_id' => auth()->user()->company_id,
              'name' => $request->name,
              'role' => 4,
              'designation' => 'Team Member',
              'email' => $request->email,
              'status' => 0,
              'approval_status' => $request->approval_status,
              'token' => hash('sha256', $plaintext)
            ]);
            
            
            $procurement = CompanyUser::where(['company_id' => $company->id, 'role' => 2])->first();

            if($member->password == ''){
                
                $details = [
                    'subject'	=>$company->name.' Invitation to Join as a Team Member',
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$member->name ?? 'User,</p>',
                    'introduction' => "<p>We are excited to inform you that you have been added as a Team Member on Dialectb2b.com by ".$procurement->name."(".$procurement->email."). </p>",
                    'body' => "<p>accept the invitation and complete your registration, please click on the following link:</p>",
                    'closing' => "<p>Dialectb2b.com is excited to welcome you to the world of B2B Sales & Sourcing.<br>
                                    If you have any questions or need assistance during the activation process,<br>
                                    please feel free to contact our customer care team via the chat box.<br>
                                    We look forward to having you on board!
                                    </p>",
                    'otp' => null,
                    'link' => url('team/signup/' . $member->token),
                    'link_text' => 'Continue to your account.',
                    "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                //'link' => url('onboarding/' . $member->token),
                $subject	= $company->name.' Invitation to Join as a Team Member';
                $htmlBody = view('email.common',compact('details'))->render();
            
                //$result = $this->sendGridEmailService->send($member->email, $subject, $htmlBody, true);
                
                \Mail::to($member->email)->send(new \App\Mail\CommonMail($details));
            }

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'New member added!',
                'member' => $member
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateMember(Request $request){
        DB::beginTransaction();
        try{
            $member = CompanyUser::findOrFail($request->id)->update([
                'status' => $request->status,
                'approval_status' => $request->approval_status,
            ]);

            $member = CompanyUser::findOrFail($request->id);
            
            $procurement = CompanyUser::where(['company_id' => $member->company_id, 'role' => 2])->first();
            
            
            if($member->status == 0){
                $subject = 'Account Deactivation Notification';
                $introduction = 'We hope this message finds you well';
                $body = 'We regret to inform you that your DialectB2B account has been deactivated, and you will no longer be able to log in. ';
                $closing = 'For further information, please contact your procurement user ('.$procurement->name.', '.$procurement->email.').';
            }
            else{
                $subject = 'Account Activation Notification';
                $introduction = 'We are pleased to inform you that your account has been successfully enabled by the procurement master user. ';
                $body = 'You can now log in to your account and access the platform.';
                $closing = 'If you have any questions or assistance, please feel free to contact our customer care team via the chat box.';
            }
            
              $details = [
                    'subject'	=> $subject,
                    'salutation' => '<p style="text-align: left;font-weight: bold;">Dear '.$member->name ?? 'User,</p>',
                    'introduction' => "<p>".$introduction."</p>",
                    'body' => "<p>".$body." </p>",
                    'closing' => "<p>".$closing."</p>",
                    'otp' => null,
                    'link' => null,
                    'link_text' => null,
                    "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
                ];
                
              
                $htmlBody = view('email.common',compact('details'))->render();
            
                //$result = $this->sendGridEmailService->send($member->email, $subject, $htmlBody, true);
                
                \Mail::to($member->email)->send(new \App\Mail\CommonMail($details));

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Member profile updated!',
                'member' => $member
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function fetchMembers(Request $request){
         $company_id = auth()->user()->company_id;
         $query = $request->input('query');

        $teamMembers = CompanyUser::where(['company_id' => $company_id,'role' => 4])->where('name', 'like', '%' . $query . '%')->get();

        $formattedTeamMembers = $teamMembers->map(function ($member) {
            return [
                'label' =>  $member->name.' - '.$member->email, 
                'value' => $member->name.' - '.$member->email,
                'id' => $member->id
            ];
        });
    
        return response()->json($formattedTeamMembers);
    }

    

}

