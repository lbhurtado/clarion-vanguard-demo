<?php

use App\Listeners\Notify\ContactAboutBroadcastApproval;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Listeners\Capture\BroadcastApproved;
use App\Repositories\BroadcastRepository;
use App\Events\ShortMessageWasRecorded;
use App\Criteria\PendingCodeCriterion;
use App\Entities\ShortMessage;
use App\Entities\Broadcast;
use App\Entities\Token;

class BroadcastApprovedTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function broadcast_approved_is_listening()
    {
        $cnt = 5;
        $message = "BroadcastApprovedTest::broadcast_approved_is_listening";

        $code = '1234';
        $token = Token::generatePending($code);
        $pending_id = $token->conjureObject()->getObject()->id;
        for ($i=1;$i<=$cnt;$i++)
        {
            factory(Broadcast::class)->create(compact('pending_id','message'));
        }
        $broadcasts = $this->app->make(BroadcastRepository::class)->skipPresenter();

        $this->assertCount($cnt, $broadcasts->getByCriteria(new PendingCodeCriterion($code))->all());

        $origin = $from = \App\Mobile::number(env('MASTER'));
        $message = "approve {$code}";
        $short_message = factory(ShortMessage::class)->create(compact('from','message'));
        $this->assertEquals($origin, $short_message->mobile);
        $this->expectsEvents(ContactAboutBroadcastApproval::class);

        $listener = $this->app->make(BroadcastApproved::class);
        $listener->handle(new ShortMessageWasRecorded($short_message));

        $this->assertTrue($listener->regexMatches($attributes));
        $this->assertEquals($code, $attributes['token']);
        $this->assertCount(0, $broadcasts->getByCriteria(new PendingCodeCriterion($code))->all());
    }
}
