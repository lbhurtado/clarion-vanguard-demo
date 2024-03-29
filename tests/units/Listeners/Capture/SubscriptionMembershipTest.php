<?php

use App\Listeners\Capture\Contact as CaptureContactListener;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Events\SubscriptionMembershipsWereProcessed;
use App\Events\ShortMessageWasRecorded;
use App\Listeners\Capture\SubscriptionMembership;
use App\Repositories\SubscriptionRepository;
use App\Entities\ShortMessage;
use App\Entities\Subscription;
use App\Entities\Token;
use App\Mobile;

class SubscriptionMembershipTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function subscription_membership_is_listening()
    {
        $subscription1 = factory(Subscription::class)->create([
            'code' => 'info',
            'description' => 'info description'
        ]);

        Token::generate($subscription1, 'info');

        $subscription2 = factory(Subscription::class)->create([
            'code' => 'about',
            'description' => 'about description'
        ]);

        Token::generate($subscription2, 'about');

        $this->expectsEvents(ShortMessageWasRecorded::class);

        $short_message = factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'message'   => "about Lester '92",
            'direction' => INCOMING
        ]);

        $capture_contact_listener = $this->app->make(CaptureContactListener::class);
        $event = new ShortMessageWasRecorded($short_message);
        $capture_contact_listener->handle($event);

        $listener = $this->app->make(SubscriptionMembership::class);
        $listener->handle($event);

        $this->assertTrue($listener->regexMatches($attributes));

        $this->assertEquals('about', $attributes['token']);
        $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
        $this->assertEquals("Lester '92", $attributes['handle']);

        $this->assertCount(1, $subscription2->contacts);

        $this->assertEquals(Mobile::number('09173011987'), $subscription2->contacts->first()->mobile);
        $this->assertEquals("Lester '92", $subscription2->contacts->first()->handle);
        $this->seeInDatabase($subscription2->contacts()->getTable(), [
            'subscription_id' => $subscription2->id,
            'contact_id' => $short_message->contact->id
        ]);
    }

    /** test */
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
