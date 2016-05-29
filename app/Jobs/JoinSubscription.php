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
     * @param ContactRepository $contacts
     * @param SubscriptionRepository $units
     */
    public function handle(ContactRepository $contacts, SubscriptionRepository $units)
    {
        $this->mappings['fields']['unit'] = 'code';
        $this->mappings['values']['token'] = 'keyword';

        $prospect = $this->getProspect($contacts);
        $unit = $this->getUnit($units);

        if ($unit) {
            $handle = $this->attributes[$this->mappings['values']['handle']];
            $this->updateHandle($prospect, $handle);
            $this->joinUnit($unit, $prospect);
        }
    }
}
