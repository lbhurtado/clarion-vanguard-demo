<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ShortMessageRepository;
use App\Jobs\RecordShortMessage;
use App\Entities\ShortMessage;
use App\Mobile;

class RecordShortMessageTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function record_short_message_does_the_job()
    {
        list($from, $to, $message, $direction) = ['09173011987', '09189362340', 'The quick brown fox...', INCOMING];

        $job = new RecordShortMessage($from, $to, $message, $direction);
        $this->dispatch($job);
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->findWhere([
            'from'      => Mobile::number('09173011987'),
            'to'        => Mobile::number('09189362340'),
            'message'   => 'The quick brown fox...',
            'direction' => INCOMING
        ])->first();

        $this->assertInstanceOf(ShortMessage::class,  $short_message);
        $this->assertEquals(Mobile::number('09173011987'),  $short_message->from);
        $this->assertEquals(Mobile::number('09189362340'),  $short_message->to);
        $this->assertEquals('The quick brown fox...',       $short_message->message);
        $this->assertEquals(INCOMING,                       $short_message->direction);
        $this->seeInDatabase($short_message->getTable(), [
            'from'      => Mobile::number('09173011987'),
            'to'        => Mobile::number('09189362340'),
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function record_short_message_respects_the_blacklist()
    {
        $this->setExpectedException(\Exception::class);

        list($from, $to, $message, $direction) = ['09171234567', '09189362340', 'The quick brown fox...', INCOMING];

        $job = new RecordShortMessage($from, $to, $message, $direction);
        $this->dispatch($job);
    }
}
