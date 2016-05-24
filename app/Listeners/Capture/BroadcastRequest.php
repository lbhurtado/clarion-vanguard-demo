<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\GroupRepository;

class BroadcastRequest extends TextCommanderListener
{

    static protected $regex = "/(?:(?:broadcast|send)\s?|@)(?<group_alias>[^\s]+)\s?(?<message>.*)/i";

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
     * @return void
     */
    protected function execute()
    {
        $group = $this->groups->findByAlias($this->group_alias)->first();

        $this->groups->generatePendingMessages($group, $this->message, $this->mobile);
    }
}
