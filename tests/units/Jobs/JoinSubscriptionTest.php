<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ContactRepository;
use App\Repositories\SubscriptionRepository;
use App\Entities\ShortMessage;
use App\Jobs\JoinSubscription;
use App\Mobile;

class JoinSubscriptionTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function join_subscription_does_the_job()
    {
        $subscriptions = $this->app->make(SubscriptionRepository::class);
        $contacts = $this->app->make(ContactRepository::class);
        $code = 'news';
        $description = 'News Description';
        $subscription = $subscriptions->create(compact('code', 'description'));
        $mobile = '09173011987';
        $contact = $contacts->create(['mobile' => $mobile, 'handle' => "Lester '91"]);

        $this->assertCount(0, $subscriptions->find($subscription->id)->contacts);

        $attributes = [
            'keyword' => $subscription->code,
            'mobile' => $contact->mobile,
            'handle' => $contact->handle,
        ];

        $job = new JoinSubscription($attributes);
        $this->dispatch($job);

        $this->assertCount(1, $subscriptions->find($subscription->id)->contacts);

        $job = new JoinSubscription($attributes);
        $this->dispatch($job);

        $this->assertCount(1, $subscriptions->find($subscription->id)->contacts);

        $this->assertEquals(Mobile::number('09173011987'), $subscription->contacts->first()->mobile);
        $this->assertEquals("Lester '91", $subscription->contacts->first()->handle);
        $this->seeInDatabase($subscription->contacts()->getTable(), [
            'subscription_id' => $subscription->id,
            'contact_id' => $contact->id
        ]);
    }
}
