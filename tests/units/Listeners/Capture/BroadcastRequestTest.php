<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Listeners\Capture\BroadcastRequest;
use App\Repositories\BroadcastRepository;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Group;
use App\Mobile;

class BroadcastRequestTest extends TestCase
{
    use DatabaseMigrations;

    private $broadcasts;

    function setUp()
    {
        parent::setUp();

        $this->broadcasts = $this->app->make(BroadcastRepository::class)->skipPresenter();
    }

    /** @test */
    function broadcast_request_is_listening()
    {
        $this->assertCount(0, $this->broadcasts->all());
        $group = factory(Group::class)->create([
            'name' => 'UP Vanguard, Inc.',
            'code' => $token = 'vanguard'
        ]);
        $cnt = 2;
        for ($i=1;$i<=$cnt;$i++)
        {
            $group->contacts()->attach(factory(Contact::class)->create());
        }
        $text = "BroadcastRequestTest::broadcast_request_is_listening";
        $messages = [
            "broadcast {$token} {$text}",
            "send {$token} $text",
            "@{$token} $text",
        ];
        $origin = Mobile::number('09189362340');
        $destination = Mobile::number('09173011987');
        foreach ($messages as $message)
        {
            $this->expectsEvents(ShortMessageWasRecorded::class);
            $short_message = factory(ShortMessage::class)->create([
                'from'      => $origin,
                'to'        => $destination,
                'message'   => $message,
                'direction' => INCOMING
            ]);
            $listener = $this->app->make(BroadcastRequest::class);
            $listener->handle(new ShortMessageWasRecorded($short_message));
            $attributes = [];
            $this->assertTrue($listener->regexMatches($attributes));
            $this->assertEquals($token, $attributes['token']);
            $this->assertEquals($origin, $attributes['mobile']);
            $this->assertEquals($text, $attributes['message']);
        }
        $this->assertCount($cnt, $group->contacts);
        $this->assertCount($cnt * count($messages), $this->broadcasts->all());
    }
}
