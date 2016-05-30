<?php

namespace App\Listeners\Capture;

use App\Repositories\SubscriptionRepository;
use App\Listeners\TextCommanderListener;
use App\Jobs\JoinSubscription;

class SubscriptionMembership extends TextCommanderListener
{
    protected $regex = "/(?<token>{App\Entities\Subscription})\s?(?<handle>.*)/i";

    protected $column = 'code';

    protected $mappings = [
        'attributes' => [
            'token'  => 'token',
        ],
    ];

    /**
     * SubscriptionMembership constructor.
     * @param $repository
     */
    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $job = new JoinSubscription($this->attributes);
        $this->dispatch($job);
    }

}
