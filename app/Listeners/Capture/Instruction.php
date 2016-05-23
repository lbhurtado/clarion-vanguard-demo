<?php

namespace App\Listeners\Capture;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;

class Instruction
{

    private $keywords;
    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $instruction = $event->shortMessage->getInstruction();

    }
}
