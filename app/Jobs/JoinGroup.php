<?php

namespace App\Jobs;

use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use Illuminate\Queue\SerializesModels;
use App\Repositories\GroupRepository;
use App\Jobs\Job;
use App\Mobile;

class JoinGroup extends Job
{
    private $group_alias;
    private $mobile;
    private $handle;
    private $new_handle = false;

    /**
     * JoinGroup constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        foreach($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param ContactRepository $contacts
     * @param GroupRepository $groups
     */
    public function handle(ContactRepository $contacts, GroupRepository $groups)
    {
        list($prospect, $brod_group) = $this->getActors($contacts, $groups);

        if ($brod_group) {
            $this->updateHandle($prospect);
            $this->joinGroup($brod_group, $prospect);
        }
    }

    /**
     * @param ContactRepository $contacts
     * @param GroupRepository $groups
     * @return array
     */
    protected function getActors(ContactRepository $contacts, GroupRepository $groups)
    {
        $contacts->skipPresenter();
        $groups->skipPresenter();
        $mobile = Mobile::number($this->mobile);
        $prospect = $contacts->findByField('mobile', $mobile)->first();
        $group = $groups->findByField('name', $this->group_alias)->first();

        return array($prospect, $group);
    }

    /**
     * @param $prospect
     */
    protected function updateHandle($prospect)
    {
        if (!is_null($this->handle)) {
            if ($prospect->handle != $this->handle)
            {
                $prospect->handle = $this->handle;
                $prospect->save();
                $this->new_handle = true;
            }
        }
    }

    /**
     * @param $group
     * @param $prospect
     */
    protected function joinGroup($group, $prospect)
    {
        $contact = $group->contacts()->where('contact_id', $prospect->id)->first();
        if (is_null($contact)) {
            $group->contacts()->attach($prospect);
            $group->save();
            event(new GroupMembershipsWereProcessed($group, $prospect, true));
        }
        elseif ($this->new_handle)
        {
            event(new GroupMembershipsWereProcessed($group, $prospect, false));
        }
    }
}
