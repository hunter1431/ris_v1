<?php

namespace App\Events;

use App\Models\RisHeader;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RisApproved
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public RisHeader $ris)
    {
    }
}
