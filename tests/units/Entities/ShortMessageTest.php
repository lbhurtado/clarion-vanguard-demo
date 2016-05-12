<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ShortMessageRepository;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;
use App\Mobile;

class ShortMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function short_message_has_from_to_message_fields()
    {
        $short_message = $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => 'The quick brown fox...',
            'direction' => INCOMING
        ]);

        $this->assertEquals(Mobile::number('09189362340'), $short_message->from);
        $this->assertEquals(Mobile::number('09173011987'), $short_message->to);
        $this->assertEquals('The quick brown fox...', $short_message->message);
        $this->assertEquals(INCOMING, $short_message->direction);

        $this->seeInDatabase($short_message->getTable(), [
            'from'      => Mobile::number('09189362340'),
            'to'        => Mobile::number('+639173011987'),
            'message'   => 'The quick brown fox...',
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function short_message_has_a_factory()
    {
        $short_message = factory(ShortMessage::class)->create([
            'from'      => '09189362340',
            'message'   => 'The quick brown fox...',
            'direction' => INCOMING
        ]);

        $this->assertEquals(Mobile::number('09189362340'), $short_message->from);
        $this->assertEquals('The quick brown fox...', $short_message->message);
        $this->assertEquals(INCOMING, $short_message->direction);
    }

    /** @test */
    function short_message_has_a_create_event()
    {
        $this->expectsEvents(ShortMessageWasRecorded::class);
        factory(ShortMessage::class)->create();
    }
}