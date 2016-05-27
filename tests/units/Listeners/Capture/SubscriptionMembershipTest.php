<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Events\SubscriptionMembershipsWereProcessed;
use App\Events\ShortMessageWasRecorded;
use App\Listeners\Capture\SubscriptionMembership;
use App\Repositories\SubscriptionRepository;
use App\Entities\ShortMessage;
use App\Entities\Subscription;
use App\Mobile;

class SubscriptionMembershipTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function subscription_membership_is_listening()
    {
        factory(Subscription::class)->create(['code' => 'info',  'description' => 'info description']);
        factory(Subscription::class)->create(['code' => 'about', 'description' => 'about description']);

        $this->expectsEvents(ShortMessageWasRecorded::class);

        $from = $mobile = '09173011987';
        $codes = $this->app->make(SubscriptionRepository::class)->skipPresenter()->all()->pluck('code')->toArray();
        foreach ($codes as $code)
        {
            $message = "{$code} arguments";
            $short_message = factory(ShortMessage::class)->create(compact('from', 'message'));
            $listener = new SubscriptionMembership(\App::make(SubscriptionRepository::class));
            $listener->handle(new ShortMessageWasRecorded($short_message));
            $this->assertTrue($listener->regexMatches($attributes));
            $this->assertEquals($code, $attributes['command']);
        }

        $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
    }

    /** @test */
    function subscription_membership_sends_feedback()
    {
        factory(Subscription::class)->create(['code' => 'info',  'description' => 'info description']);
        factory(Subscription::class)->create(['code' => 'about', 'description' => 'about description']);

//        $this->expectsEvents(SubscriptionMembershipsWereProcessed::class);
        $from = $mobile = '09173011987';
        $codes = $this->app->make(SubscriptionRepository::class)->skipPresenter()->all()->pluck('code')->toArray();
        foreach ($codes as $code)
        {
            $message = "{$code} arguments";
            $short_message = factory(ShortMessage::class)->create(compact('from', 'message'));
        }

    }
}
