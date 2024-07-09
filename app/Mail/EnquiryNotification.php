<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Enquiry;

class EnquiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;

    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function build()
    {
        return $this->view('email.enquiry-notification')
                    ->subject('New Enquiry Received');
    }

}
