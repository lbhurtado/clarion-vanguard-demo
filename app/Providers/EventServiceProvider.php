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
//            \App\Listeners\Capture\BroadcastRequest::class,
//            \App\Listeners\Capture\BroadcastApproved::class,
//            \App\Listeners\Capture\InfoRequest::class,
//            \App\Listeners\Capture\SubscriptionMembership::class,
        ],
        'App\Events\BlacklistedNumberDetected' => [
            'App\Listeners\Validate\ActionAboutBlacklistedNumberDetection',
            'App\Listeners\Notify\ContactAboutBlacklistedNumberDetection',
            'App\Listeners\Relay\ToOthersAboutBlacklistedNumberDetection',
        ],
        'App\Events\WhitelistedNumberNotDetected' => [
            'App\Listeners\Validate\ActionAboutWhitelistedNumberNonDetection',
            'App\Listeners\Notify\ContactAboutWhitelistedNumberNonDetection',
            'App\Listeners\Relay\ToOthersAboutWhitelistedNumberNonDetection',
        ],
//        \App\Events\ContactWasCreated::class => [
//            'App\Listeners\Notify\ContactAboutContactCreation',
//            'App\Listeners\Relay\ToOthersAboutContactCreation',
//        ],
        \App\Events\GroupMembershipsWereProcessed::class => [
//            \App\Listeners\Notify\ContactAboutGroupMembershipProcessing::class,
//            '\App\Listeners\Relay\ToOthersAboutGroupMembershipProcessing',
        ],
        \App\Events\SubscriptionMembershipsWereProcessed::class => [
            'App\Listeners\Notify\ContactAboutSubscriptionMembershipProcessing',
            'App\Listeners\Relay\ToOthersAboutSubscriptionMembershipProcessing',
        ],
        \App\Events\BroadcastWasRequested::class => [
            \App\Listeners\Notify\ContactAboutBroadcastRequest::class,
            \App\Listeners\Relay\ToOthersAboutBroadcastRequest::class,
        ],
        \App\Events\BroadcastWasApproved::class => [
            'App\Listeners\Notify\ContactAboutBroadcastApproval',
            'App\Listeners\Relay\ToOthersAboutBroadcastApproval',
        ],
//        'App\Events\BroadcastWasSent' => [
//            'App\Listeners\Notify\ContactAboutBroadcastSending',
//            'App\Listeners\Relay\ToOthersAboutBroadcastSending',
//        ],
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
