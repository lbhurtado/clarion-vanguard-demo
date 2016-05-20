<?php

namespace App\Listeners\Validate;

use App\Exceptions\WhitelistedNumberException;
use App\Events\WhitelistedNumberNotDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ActionAboutWhitelistedNumberNonDetection
{
    public function handle(WhitelistedNumberNotDetected $event)
    {
        throw new WhitelistedNumberException("Number {$event->attributes['from']} not whitelisted!");
    }
}
