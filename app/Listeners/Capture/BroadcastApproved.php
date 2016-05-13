<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\PendingRepository;
use App\Events\ShortMessageWasRecorded;
use App\Criteria\TokenCriterion;
use App\Jobs\SendShortMessage;
use App\Instruction;

class BroadcastApproved
{
    use DispatchesJobs;

    private $pendings;

    /**
     * BroadcastApproved constructor.
     * @param $pendings
     */
    public function __construct(PendingRepository $pendings)
    {
        $this->pendings = $pendings->skipPresenter();
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
        {
            $keyword = strtoupper($instruction->getKeyword());
            if ($keyword == strtoupper(Instruction::$keywords['APPROVE']))
            {
                $token = $instruction->getArguments();
                $pendings = $this->pendings->getByCriteria(new TokenCriterion($token))->all();
                foreach($pendings as $pending)
                {
                    $job = new SendShortMessage($pending->to, $pending->message);

                    $this->dispatch($job);
                    $pending->delete();
                }
            }
        }
    }
}
