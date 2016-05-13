<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Jobs\SendShortMessage;
use App\Instruction;

class BroadcastRequest
{
    use DispatchesJobs;

    private $groups;

    /**
     * @param GroupRepository $groups
     */
    public function __construct(GroupRepository $groups)
    {
        $this->groups = $groups->skipPresenter();
    }

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $instruction = $event->shortMessage->getInstruction();

        if ($instruction->isValid()) {
            $keyword = $instruction->getKeyword();
            switch ($keyword)
            {
                case strtoupper(Instruction::$keywords['All']):
                    $group = $this->groups->findByField('name', 'brods')->first();
                    $message = $event->shortMessage->getInstruction()->getArguments();
                    $origin = $event->shortMessage->mobile;
                    $this->groups->generatePendingMessages($group, $message, $origin);

                    break;
            }
        }
    }
}
