<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use Illuminate\Queue\SerializesModels;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use App\Jobs\Job;
use App\Mobile;

class JoinGroup extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $group_name;
    private $mobile;
    private $handle;

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
        $contacts->skipPresenter();
        $groups->skipPresenter();
        $mobile = Mobile::number($this->mobile);
        $prospect = $contacts->findByField('mobile', $mobile)->first();
        $brod_group = $groups->findByField('name', 'brods')->first();

        if ($brod_group) {
            $contact = $brod_group->contacts()->where('contact_id', $prospect->id)->first();
            if (is_null($contact))
            {
                if (!is_null($this->handle)) {
                    $prospect->handle = $this->handle;
                    $prospect->save();
                }
                $brod_group->contacts()->attach($prospect);
                $brod_group->save();
            }

        }
    }
}
