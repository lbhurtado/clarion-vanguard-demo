<?php

namespace App\Listeners\Relay;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use App\Repositories\PendingRepository;
use App\Events\BroadcastWasRequested;
use App\Criteria\TokenCriterion;
use App\Jobs\SendShortMessage;

class ToOthersAboutBroadcastRequest
{
    use DispatchesJobs;

    private $contacts;

    private $pendings;

    /**
     * @param ContactRepository $contacts
     */
    public function __construct(ContactRepository $contacts, PendingRepository $pendings)
    {
        $this->contacts = $contacts->skipPresenter();
        $this->pendings = $pendings->skipPresenter();
    }

    /**
     * Handle the event.
     *
     * @param  BroadcastWasRequested  $event
     * @return void
     */
    public function handle(BroadcastWasRequested $event)
    {
        list($mobile, $message) = $this->generateNotificationParameters($event->origin, $event->message, $event->group, $event->token);
        $job = new SendShortMessage($mobile, $message);

        $this->dispatch($job);
    }

    /**
     * @param $origin
     * @param $message
     * @param $group
     * @param $token
     * @return array
     */
    protected function generateNotificationParameters($origin, $message, $group, $token)
    {
        $mobile = env('MASTER');
//        dd($origin);
//        dd($this->contacts->all()->pluck('mobile'));
//        dd($this->contacts->findByField('mobile', $origin)->first());
        $handle = $this->contacts->findByField('mobile', $origin)->first()->handle;
        $number_of_pendings = count($this->pendings->getByCriteria(new TokenCriterion($token))->all());
        $message = "$handle ($mobile) is requesting to send the ff: msg. '{$message}' to the group '{$group->name}'[{$number_of_pendings}]. Send 'APPROVE {$token}' to allow the broadcast.";

        return array($mobile, $message);
    }
}
