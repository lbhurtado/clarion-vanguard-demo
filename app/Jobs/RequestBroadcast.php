<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendShortMessage;
use App\Entities\Group;
use App\Jobs\Job;

class RequestBroadcast extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $group;

    private $message;

    private $origin;

    public function __construct(Group $group, $message, $origin)
    {
        $this->group = $group;
        $this->message = $message;
        $this->origin = $origin;
    }

    /**
     * @param ContactRepository $contacts
     */
    public function handle(ContactRepository $contacts)
    {
//        $mobile = env('MASTER');
//        $handle = $contacts->findByField('mobile', $this->origin)->first()->handle;
//        $message = "$handle ($mobile) is requesting to send the ff: msg. '{$this->message}' to the group '{$this->group->name}'. Send 1234 to approve.";

//        $job = new SendShortMessage($mobile, $message);
//        $this->dispatch($job);
    }
}
