<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\PendingRepository;
use App\Criteria\TokenCriterion;
use App\Jobs\SendShortMessage;

class BroadcastApproved extends TextCommanderListener
{
    static protected $regex = "/(approve)\s?(?<token>.*)/i";

    private $pendings;

    /**
     * @param $pendings
     */
    public function __construct(PendingRepository $pendings)
    {
        $this->pendings = $pendings->skipPresenter();
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $pendings = $this->pendings->getByCriteria(new TokenCriterion($this->token))->all();
        foreach($pendings as $pending)
        {
            $job = new SendShortMessage($pending->to, $pending->message);

            $this->dispatch($job);
            $pending->delete();
        }
    }

}
