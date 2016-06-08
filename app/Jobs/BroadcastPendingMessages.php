<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\BroadcastRepository;
use App\Criteria\PendingCodeCriterion;
use App\Entities\Pending;

class BroadcastPendingMessages extends Job
{
    use DispatchesJobs;

    private $pending;

    /**
     * @param Pending $pending
     */
    public function __construct(Pending $pending)
    {
        $this->pending = $pending;
    }

    /**
     * @param BroadcastRepository $broadcasts
     */
    public function handle(BroadcastRepository $broadcasts)
    {
        $pendings = $broadcasts->skipPresenter(true)->getByCriteria(new PendingCodeCriterion($this->pending->code))->all();
        foreach($pendings as $pending)
        {
            $job = new SendShortMessage($pending->to, $pending->message);
            $this->dispatch($job);
            $pending->delete();
        }
    }
}
