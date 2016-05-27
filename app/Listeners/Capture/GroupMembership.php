<?php

namespace App\Listeners\Capture;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Jobs\JoinGroup;

class GroupMembership extends TextCommanderListener
{
    protected $regex = "/(?<group_alias>[^\s]+)\s?(?<handle>.*)/i";

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $job = new JoinGroup($this->attributes);
        $this->dispatch($job);
    }
}
