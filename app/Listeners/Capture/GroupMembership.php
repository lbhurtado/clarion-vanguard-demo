<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\JoinGroup;
use App\Instruction;

class GroupMembership
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $instruction = $event->shortMessage->getInstruction();

        if ($instruction->isValid())
        {
            switch ($instruction->getKeyword())
            {
                case strtoupper(Instruction::$keywords['REGISTRATION']):
                    $job = new JoinGroup($instruction->getKeyword(), $event->shortMessage->mobile, $instruction->getArguments());
                    $this->dispatch($job);
                    break;
            }
        }

    }
}
