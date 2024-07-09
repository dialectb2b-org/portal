<?php

namespace App\Jobs;

use App\Models\CompanyUser;
use App\Models\CompanyActivity;
use App\Models\Enquiry;
use App\Models\EnquiryRelation;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoadUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(CompanyUser $user)
    {
         $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user_id = $this->user->id;
        $user = CompanyUser::find($user_id);
        
        $company_activity = CompanyActivity::where('company_id',$user->company_id)->get();
        
        $enquiries = Enquiry::whereIn('sub_category_id', $company_activity->pluck('activity_id'))
            ->where('company_id','!=', $user->company_id)
            ->where('expired_at', '>', now())
            ->get();
            
             foreach ($enquiries as $enquiry) {
                  EnquiryRelation::insert([
                        'enquiry_id' => $enquiry->id,
                        'recipient_company_id' => $user->company_id,
                        'to_id' =>	$user->id,
                        'is_read' => 0,	
                        'is_replied' => 0
                    ]);
                    
                    Notification::create([
                        'company_id' => $user->company_id,
                        'user_id' => $user->id,
                        'type' => 1,
                        'role' => 3,
                        'title' => 'New RFQ Receieved - '.$enquiry->reference_no,
                        'message' => 'New RFQ Receieved - '.$enquiry->reference_no,
                        'action' => '', 
                        'action_url' => '' 
                        ]);
             }
        
    }
}
