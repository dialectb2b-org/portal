<?php

namespace App\Listeners;

use App\Events\SalesUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\LoadUserDataJob;

class LoadUserData
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
    public function handle(SalesUserCreated $event): void
    {
        dispatch(new LoadUserDataJob($event->user));
    }
}
