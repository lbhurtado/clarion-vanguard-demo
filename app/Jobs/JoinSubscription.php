<?php

namespace App\Jobs;

use App\Events\SubscriptionMembershipsWereProcessed;
use App\Repositories\ContactRepository;
use App\Repositories\SubscriptionRepository;

class JoinSubscription extends JoinUnit
{
    protected $event = SubscriptionMembershipsWereProcessed::class;

    /**
     * @param ContactRepository $contacts
     * @param SubscriptionRepository $units
     */
    public function handle(ContactRepository $contacts, SubscriptionRepository $units)
    {
        $this->mappings['fields']['unit'] = 'code';

        $this->setupContacts($contacts, $prospect);
        $this->setupUnits($units, $unit);

        if ($handle = $this->attributes[$this->mappings['values']['handle']])
        {
            $this->updateHandle($prospect, $handle);
        }

        $this->joinUnit($unit, $prospect);
    }
}
