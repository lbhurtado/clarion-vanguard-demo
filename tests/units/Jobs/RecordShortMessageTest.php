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
         list($from, $to, $message, $direction) =
             [
                 Mobile::number('09173011987'),
                 Mobile::number('09189362340'),
                 'The quick brown fox...',
                 INCOMING
             ];
        $job = new RecordShortMessage($from, $to, $message, $direction);
        $this->dispatch($job);
        $attributes = compact('from','to','message', 'direction');
        $short_message = $this->app->make(ShortMessageRepository::class)
            ->findWhere($attributes)
            ->first();
        $this->assertInstanceOf(ShortMessage::class, $short_message);
        $this->assertEquals($from, $short_message->from);
        $this->assertEquals($to, $short_message->to);
        $this->assertEquals($message, $short_message->message);
        $this->assertEquals($direction, $short_message->direction);
        $this->seeInDatabase($short_message->getTable(), $attributes);
    }

    /** @test */
    function record_short_message_creates_singleton_instance()
    {
        list($from, $to, $message, $direction) =
            [
                Mobile::number('09173011987'),
                Mobile::number('09189362340'),
                'The quick brown fox...',
                INCOMING
            ];
        $job = new RecordShortMessage($from, $to, $message, $direction);
        $this->dispatch($job);
        $short_message = $this->app->make(ShortMessage::class); //this is the singleton instance
        $this->assertInstanceOf(ShortMessage::class, $short_message);
        $this->assertEquals($from, $short_message->from);
        $this->assertEquals($to, $short_message->to);
        $this->assertEquals($message, $short_message->message);
        $this->assertEquals($direction, $short_message->direction);
        $this->seeInDatabase($short_message->getTable(), compact('from','to','message', 'direction'));
    }
}
