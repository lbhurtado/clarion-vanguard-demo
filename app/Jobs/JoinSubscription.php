<?php

namespace App\Jobs;

use App\Events\SubscriptionMembershipsWereProcessed;
use App\Repositories\ContactRepository;
use App\Repositories\SubscriptionRepository;

class JoinSubscription extends JoinUnit
{
    protected $column = 'code';
    protected $event = SubscriptionMembershipsWereProcessed::class;

    /**
     * @param ContactRepository $contactRepository
     * @param SubscriptionRepository $unitRepository
     */
    public function handle(ContactRepository $contactRepository, SubscriptionRepository $unitRepository)
    {
        list($prospect, $unit) = $this->getProspectAndUnit($contactRepository, $unitRepository);

        if ($unit) {
            $this->updateHandleOfContact($prospect, $this->handle);
            $this->joinUnit($unit, $prospect);
        }
    }
}
