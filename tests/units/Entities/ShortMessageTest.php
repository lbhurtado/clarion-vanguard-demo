<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\ShortMessageRepository;

class ShortMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function short_message_has_from_to_message_fields()
    {
        $short_message = $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => 'The quick brown fox...'
        ]);

        $this->assertEquals('09189362340', $short_message->from);
        $this->assertEquals('09173011987', $short_message->to);
        $this->assertEquals('The quick brown fox...', $short_message->message);
    }
}
