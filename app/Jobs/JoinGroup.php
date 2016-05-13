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
    private $group_name;
    private $mobile;
    private $handle;
    private $new_handle = false;

    /**
     * JoinGroup constructor.
     * @param $group_name
     * @param $mobile
     * @param $handle
     */
    public function __construct($group_name, $mobile, $handle = null)
    {
        $this->group_name = $group_name;
        $this->mobile = $mobile;
        $this->handle = $handle;
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
        $brod_group = $groups->findByField('name', 'brods')->first();
        return array($prospect, $brod_group);
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
     * @param $brod_group
     * @param $prospect
     */
    protected function joinGroup($brod_group, $prospect)
    {
        $contact = $brod_group->contacts()->where('contact_id', $prospect->id)->first();
        if (is_null($contact)) {
            $brod_group->contacts()->attach($prospect);
            $brod_group->save();
            event(new GroupMembershipsWereProcessed($brod_group, $prospect, true));
        }
        elseif ($this->new_handle)
        {
            event(new GroupMembershipsWereProcessed($brod_group, $prospect, false));
        }
    }
}
