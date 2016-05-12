<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendShortMessage;
use App\Entities\Group;
use App\Jobs\Job;

class BroadcastToGroup extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $group;

    private $message;

    public function __construct(Group $group, $message)
    {
        $this->group = $group;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->group->contacts as $contact)
        {
            $job = new SendShortMessage($contact->mobile, $this->message);
            $this->dispatch($job);
        }
    }
}
