<?php

namespace App\Services;

use SendGrid\Mail\Mail;
use SendGrid;
use Illuminate\Support\Facades\Log;


class SendGridEmailService
{
    protected $sendGrid;

    public function __construct(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    public function send($to, $subject, $body, $isHtml = false)
    {
        $email = new Mail();
        $email->setFrom('info@dialectb2b.com', 'DialectB2b.com');
        $email->setSubject($subject);
        $email->addTo($to);

        if ($isHtml) {
            $email->addContent("text/html", $body);
        } else {
            $email->addContent("text/plain", $body);
        }

        try {
            $response = $this->sendGrid->send($email);
            
            Log::build([
  'driver' => 'single',
  'path' => storage_path('logs/custom.log'),
])->info($response->statusCode(). '' .$response->body());

            return $response->statusCode() === 202;
        } catch (\Exception $e) {
                   Log::build([
  'driver' => 'single',
  'path' => storage_path('logs/custom.log'),
])->info($e);
            return false;
        }
    }
}
