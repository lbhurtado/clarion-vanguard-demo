<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\WhitelistedNumberRepository;
use App\Exceptions\WhitelistedNumberException;
use App\Events\WhitelistedNumberNotDetected;
use App\Entities\WhitelistedNumber;
use App\Entities\ShortMessage;
use App\Mobile;

class WhitelistedNumberTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function whitelisted_number_has_mobile_field()
    {
        $whitelisted_number = $this->app->make(WhitelistedNumberRepository::class)->create(['mobile'=>'09173011987']);

        $this->assertEquals(Mobile::number('09173011987'), $whitelisted_number->mobile);
        $this->seeInDatabase($whitelisted_number->getTable(), [
            'mobile' => Mobile::number('09173011987')
        ]);
    }

    /** @test */
    function whitelisted_number_has_unique_mobile_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $this->app->make(WhitelistedNumberRepository::class)->create(['mobile'=>'09173011987']);
        $this->app->make(WhitelistedNumberRepository::class)->create(['mobile'=>'09173011987']);
    }

    /** @test */
    function incoming_short_message_detects_non_whitelisted_number()
    {
        $mobile = '09161234567';
        $from = '09173011987';
        $direction = INCOMING;
        factory(WhitelistedNumber::class)->create(compact('mobile'));
        $this->expectsEvents(WhitelistedNumberNotDetected::class);

        factory(ShortMessage::class)->create(compact('from', 'direction'));
    }

    /** @test */
    function outgoing_short_message_detects_whitelisted_number()
    {
        $mobile = '09161234567';
        $to = '09173011987';
        $direction = OUTGOING;
        factory(WhitelistedNumber::class)->create(compact('mobile'));
        $this->expectsEvents(WhitelistedNumberNotDetected::class);

        factory(ShortMessage::class)->create(compact('to', 'direction'));
    }

    /** @test */
    function incoming_short_message_validates_against_whitelisted_numbers()
    {
        $mobile = '09161234567';
        $from = '09173011987';
        $direction = INCOMING;
        factory(WhitelistedNumber::class)->create(compact('mobile'));

        $this->setExpectedException(WhitelistedNumberException::class);

        factory(ShortMessage::class)->create(compact('from', 'direction'));
    }

    /** @test */
    function outgoing_short_message_validates_against_whitelisted_numbers()
    {
        $mobile = '09161234567';
        $to = '09173011987';
        $direction = OUTGOING;
        factory(WhitelistedNumber::class)->create(compact('mobile'));

        $this->setExpectedException(WhitelistedNumberException::class);

        factory(ShortMessage::class)->create(compact('to', 'direction'));
    }
}
