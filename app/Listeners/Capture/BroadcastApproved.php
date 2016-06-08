<?php

namespace App\Listeners\Capture;

use App\Listeners\Notify\ContactAboutBroadcastApproval;
use App\Listeners\Relay\ToOthersAboutBroadcastApproval;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\BroadcastRepository;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\PendingRepository;
use App\Jobs\BroadcastPendingMessages;
use App\Criteria\PendingCodeCriterion;
use App\Criteria\CodeCriterion;
use App\Jobs\SendShortMessage;


class BroadcastApproved extends TextCommanderListener
{
    protected $regex = "/(approve)\s?(?<token>{App\Entities\Pending})\s?(?<arguments>.*)/i";

    protected $column = 'code';

    protected $mappings = [
        'attributes' => [
            'token'  => 'token',
        ],
    ];

    /**
     * @param PendingRepository $repository
     */
    public function __construct(PendingRepository $repository)
    {
        $this->repository = $repository->skipPresenter(true);
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        event(new ContactAboutBroadcastApproval());
        event(new ToOthersAboutBroadcastApproval());
        $pending = $this->repository->getByCriteria(new CodeCriterion($this->attributes['token']))->first();
        $job = new BroadcastPendingMessages($pending);
        $this->dispatch($job);
    }

}
