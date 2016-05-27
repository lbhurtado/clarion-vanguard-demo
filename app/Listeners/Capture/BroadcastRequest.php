<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\GroupRepository;

class BroadcastRequest extends TextCommanderListener
{

    protected $regex = "/(?:(?:broadcast|send)\s?|@)(?<group_alias>[^\s]+)\s?(?<message>.*)/i";

    private $groups;

    /**
     * @param GroupRepository $groups
     */
    public function __construct(GroupRepository $groups)
    {
        $this->groups = $groups->skipPresenter();
    }

    /**
     * Handle the event if regex matches.
     *
     * @return string
     */
    protected function execute()
    {
        $group = $this->groups->findByAlias($this->group_alias)->first();

        return $this->groups->generatePendingMessages($group, $this->message, $this->mobile);
    }
}
