<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ShortMessageWasRecorded' => [
            'App\Listeners\Capture\Contact',
            'App\Listeners\Capture\GroupMembership',
        ],
        'App\Events\ContactWasCreated' => [
            'App\Listeners\Notify\ContactAboutContactCreation',
            'App\Listeners\Relay\ToOthersAboutContactCreation',
        ],
        'App\Events\GroupMembershipsWereProcessed' => [
            'App\Listeners\Notify\ContactAboutGroupMembershipProcessing',
            'App\Listeners\Relay\ToOthersAboutGroupMembershipProcessing',
        ],
        'App\Events\BroadcastWasRequested' => [
            'App\Listeners\Notify\ContactAboutBroadcastRequest',
            'App\Listeners\Relay\ToOthersAboutBroadcastRequest',
        ],
        'App\Events\BroadcastWasApproved' => [
            'App\Listeners\Notify\ContactAboutBroadcastApproval',
            'App\Listeners\Relay\ToOthersAboutBroadcastApproval',
        ],
        'App\Events\BroadcastWasSent' => [
            'App\Listeners\Notify\ContactAboutBroadcastSending',
            'App\Listeners\Relay\ToOthersAboutBroadcastSending',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
