<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\GroupRepository;
use App\Jobs\BroadcastToGroup;
use App\Instruction;

class BroadcastRequest
{
    use DispatchesJobs;

    private $groups;

    /**
     * BroadcastRequest constructor.
     * @param $groups
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

        if ($instruction->isValid())
            switch ($instruction->getKeyword())
            {
                case strtoupper(Instruction::$keywords['Brothers']):
                    $group = $this->groups->findByField('name', 'brods')->first();
                    $message = $event->shortMessage->getInstruction()->getArguments();
                    $job = new BroadcastToGroup($group, $message);
                    $this->dispatch($job);
                    break;
            }
    }
}
