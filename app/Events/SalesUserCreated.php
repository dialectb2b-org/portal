<?php

namespace App\Events;

use App\Models\CompanyUser;
use Illuminate\Foundation\Events\Dispatchable;

class SalesUserCreated
{
    use Dispatchable;

    public $user;

    public function __construct(CompanyUser $user)
    {
        $this->user = $user;
    }
}
