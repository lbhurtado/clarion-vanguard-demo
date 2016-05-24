<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Listeners\Capture\BroadcastRequest;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use App\Entities\Group;
use App\Mobile;

class BroadcastRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function broadcast_request_is_listening()
    {
        factory(Group::class)->create([
            'name' => 'UP Vanguard, Inc.',
            'alias' => 'vanguard'
        ]);

        $messages = [
            "broadcast vanguard Hello there!",
            "send vanguard Hello there!",
            "@vanguard Hello there!",
        ];

        foreach ($messages as $message)
        {
            $short_message = factory(ShortMessage::class)->create([
                'from'      => '09173011987',
                'message'   => $message,
                'direction' => INCOMING
            ]);
            $listener = new BroadcastRequest(\App::make(GroupRepository::class));
            $listener->handle(new ShortMessageWasRecorded($short_message));

            $this->assertTrue($listener->regexMatches($attributes));
            $this->assertEquals('vanguard', $attributes['group_alias']);
            $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
            $this->assertEquals("Hello there!", $attributes['message']);
        }
    }
}
