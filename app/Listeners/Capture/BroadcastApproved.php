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
    protected $regex = "/(approve)\s?(?<token>{App\Entities\Subscription})\s?(?<arguments>.*)/i";

    protected $column = 'token';

    protected $mappings = [
        'attributes' => [
            'token'  => 'token',
        ],
    ];

    /**
     * @param $repository
     */
    public function __construct(PendingRepository $repository)
    {
        $this->repository = $repository->skipPresenter();
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $pendings = $this->repository->getByCriteria(new TokenCriterion($this->attributes['token']))->all();
        foreach($pendings as $pending)
        {
            $job = new SendShortMessage($pending->to, $pending->message);

            $this->dispatch($job);
            $pending->delete();
        }
    }

}
