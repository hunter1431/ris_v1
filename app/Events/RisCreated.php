<?php

namespace App\Events;

use App\Models\RisHeader;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RisCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public RisHeader $ris)
    {
    }
}
