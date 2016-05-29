<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Listeners\Capture\BroadcastRequest;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\PendingRepository;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Group;
use App\Mobile;

class BroadcastRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function broadcast_request_is_listening()
    {
        $pendings = $this->app->make(PendingRepository::class);

        $this->assertCount(0, $pendings->all()['data']);

        $group = factory(Group::class)->create([
            'name' => 'UP Vanguard, Inc.',
            'alias' => 'vanguard'
        ]);

        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->create();
        $group->contacts()->attach($contact1);
        $group->contacts()->attach($contact2);

        $messages = [
            "broadcast vanguard Hello there!",
//            "send vanguard Hello there!",
//            "@vanguard Hello there!",
        ];

        foreach ($messages as $message)
        {
            $this->expectsEvents(ShortMessageWasRecorded::class);

            $short_message = factory(ShortMessage::class)->create([
                'from'      => '09173011987',
                'to'      => '09189362340',
                'message'   => $message,
                'direction' => INCOMING
            ]);

            $listener = new BroadcastRequest(\App::make(GroupRepository::class));
            $listener->handle(new ShortMessageWasRecorded($short_message));

            $this->assertTrue($listener->regexMatches($attributes));
            $this->assertEquals('vanguard', $attributes['token']);
            $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
            $this->assertEquals("Hello there!", $attributes['message']);
        }

        $this->assertCount(1, $messages);
        $this->assertCount(2, $group->contacts);
        $this->assertCount(2, $pendings->all()['data']);
    }
}
