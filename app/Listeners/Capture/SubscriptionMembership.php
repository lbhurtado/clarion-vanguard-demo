<?php

namespace App\Listeners\Capture;

use App\Repositories\SubscriptionRepository;
use App\Listeners\TextCommanderListener;
use App\Jobs\JoinSubscription;

class SubscriptionMembership extends TextCommanderListener
{
//    static protected $regex = "/(?<command>%s)\s?(?<arguments>.*)/i";

    /**
     * SubscriptionMembership constructor.
     * @param $subscriptions
     */
    public function __construct(SubscriptionRepository $subscriptions)
    {
        $this->repository = $subscriptions;
        $this->populateRegex('code');
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
