<?php

namespace App\Listeners;

use App\Mail\EnquiryNotification;
use App\Events\EnquiryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\PreRegisteredCompany;

class PreRegisteredEmails implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EnquiryCreated $event): void
    {
        $enquiry = $event->enquiry;

        $preregistered = PreRegisteredCompany::where('pre_registered_companies.country_id', $enquiry->country_id)
                        ->where('pre_registered_company_activities.activity_id', $enquiry->sub_category_id)
                        ->select( 'pre_registered_companies.id', 'name', 'email','alt_email_1','alt_email_2')
                        ->join('pre_registered_company_activities', 'pre_registered_company_activities.company_id', '=', 'pre_registered_companies.id')                              
                        ->get();   

        $recipients = ['email1@example.com', 'email2@example.com'];

        // Send bulk emails
        foreach ($preregistered as $recipient) {
           // Mail::to($recipient->email)->send(new EnquiryNotification($enquiry));
        }
    }
}
