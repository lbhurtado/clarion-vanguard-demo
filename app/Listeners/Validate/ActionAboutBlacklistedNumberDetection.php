<?php

namespace App\Listeners\Validate;

use App\Exceptions\BlacklistedNumberException;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\BlacklistedNumberDetected;
use Illuminate\Queue\InteractsWithQueue;

class ActionAboutBlacklistedNumberDetection
{
    public function handle(BlacklistedNumberDetected $event)
    {
        throw new BlacklistedNumberException("Blacklisted number {$event->attributes['from']}!");
    }
}
