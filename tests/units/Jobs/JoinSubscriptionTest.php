<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\SubscriptionRepository;
use App\Repositories\ContactRepository;
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
        list($code, $description) = ['news', 'News Description'];
        $subscription = $subscriptions->create(compact('code', 'description'));
        list($mobile, $handle) = [Mobile::number('09173011987'), "Lester '91" ];
        $contact = $contacts->create(compact('mobile', 'handle'));
        $this->assertCount(0, $subscriptions->find($subscription->id)->contacts);
        $token = $subscription->code;
        $attributes = compact('token', 'mobile', 'handle');
        for ($i = 0; $i <= 5; $i++)
        {
            $job = new JoinSubscription($attributes);
            $this->dispatch($job);
        }
        $this->assertCount(1, $subscriptions->find($subscription->id)->contacts);
        $this->assertEquals($mobile, $subscription->contacts->first()->mobile);
        $this->assertEquals($handle, $subscription->contacts->first()->handle);
        $this->seeInDatabase($subscription->contacts()->getTable(), [
            'subscription_id' => $subscription->id,
            'contact_id' => $contact->id
        ]);
    }
}
