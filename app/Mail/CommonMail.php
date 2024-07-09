<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Company;

class CommonMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public function __construct($details)
    {
        $this->details = $details;
    }
   
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $email = $this->subject($this->details['subject'])
                      ->view('email.common') // your email view
                      ->with('details', $this->details);

        // Check if PDF content is available and attach it
        if (isset($this->details['pdf']) && isset($this->details['pdf_name'])) {
            $email->attachData($this->details['pdf'], $this->details['pdf_name'], [
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
    
}