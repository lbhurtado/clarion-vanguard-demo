<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ShortMessageWasRecorded::class => [
            \App\Listeners\Capture\Contact::class,
            \App\Listeners\Capture\GroupMembership::class,
            \App\Listeners\Capture\BroadcastRequest::class,
            'App\Listeners\Capture\BroadcastApproved',
        ],
        \App\Events\ContactWasCreated::class => [
            'App\Listeners\Notify\ContactAboutContactCreation',
            'App\Listeners\Relay\ToOthersAboutContactCreation',
        ],
        \App\Events\GroupMembershipsWereProcessed::class => [
            'App\Listeners\Notify\ContactAboutGroupMembershipProcessing',
            'App\Listeners\Relay\ToOthersAboutGroupMembershipProcessing',
        ],
        \App\Events\BroadcastWasRequested::class => [
            \App\Listeners\Notify\ContactAboutBroadcastRequest::class,
            \App\Listeners\Relay\ToOthersAboutBroadcastRequest::class,
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
