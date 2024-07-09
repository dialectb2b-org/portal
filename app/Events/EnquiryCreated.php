<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Enquiry;

class EnquiryCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $enquiry;
    /**
     * Create a new event instance.
     */
    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    
}
