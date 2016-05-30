<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Listeners\Capture\BroadcastApproved;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\PendingRepository;
use App\Entities\ShortMessage;
use App\Entities\Pending;

class BroadcastApprovedTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function broadcast_approved_is_listening()
    {
        factory(Pending::class)->create([
            'from'    => '09189362340',
            'to'      => '+639178251991',
            'message' => 'The quick brown fox...',
            'token'   => '1234'
        ]);

        factory(Pending::class)->create([
            'from'    => '09189362340',
            'to'      => '+639178251991',
            'message' => 'The quick brown fox...',
            'token'   => '1234'
        ]);

        $pendings = $this->app->make(PendingRepository::class);

        $this->assertCount(2, $pendings->all()['data']);

        $short_message = factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'message'   => 'approve 1234',
            'direction' => INCOMING
        ]);

        $listener = new BroadcastApproved(\App::make(PendingRepository::class));
        $listener->handle(new ShortMessageWasRecorded($short_message));

        $this->assertTrue($listener->regexMatches($attributes));
        $this->assertEquals('1234', $attributes['token']);
        $this->assertCount(0, $pendings->all()['data']);
    }
}
