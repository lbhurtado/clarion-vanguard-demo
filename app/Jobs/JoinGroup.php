<?php

namespace App\Jobs;

use App\Events\GroupMembershipsWereProcessed;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;

class JoinGroup extends JoinUnit
{
    protected $event = GroupMembershipsWereProcessed::class;

    /**
     * @param ContactRepository $contacts
     * @param GroupRepository $units
     */
    public function handle(ContactRepository $contacts, GroupRepository $units)
    {
        $this->mappings['fields']['unit'] = 'alias';
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
