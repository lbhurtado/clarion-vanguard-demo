<?php

namespace App\Jobs;

use App\Events\GroupMembershipsWereProcessed;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;

class JoinGroup extends JoinUnit
{
    protected $column = 'name';
    protected static $event = GroupMembershipsWereProcessed::class;

    /**
     * @param ContactRepository $contactRepository
     * @param GroupRepository $unitRepository
     */
    public function handle(ContactRepository $contactRepository, GroupRepository $unitRepository)
    {
        list($prospect, $unit) = $this->getProspectAndUnit($contactRepository, $unitRepository);

        if ($unit) {
            $this->updateHandleOfContact($prospect, $this->handle);
            $this->joinUnit($unit, $prospect);
        }
    }
}
