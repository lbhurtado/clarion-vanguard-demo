<?php


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\BlacklistedNumberRepository;
use App\Exceptions\BlacklistedNumberException;
use App\Events\BlacklistedNumberDetected;
use App\Entities\BlacklistedNumber;
use App\Entities\ShortMessage;
use App\Mobile;

class BlacklistedNumberTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function blacklisted_number_has_mobile_field()
    {
        $blacklisted_number = $this->app->make(BlacklistedNumberRepository::class)->create(['mobile'=>'09173011987']);

        $this->assertEquals(Mobile::number('09173011987'), $blacklisted_number->mobile);
        $this->seeInDatabase($blacklisted_number->getTable(), [
            'mobile' => Mobile::number('09173011987')
        ]);
    }

    /** @test */
    function blacklisted_number_has_unique_mobile_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $this->app->make(BlacklistedNumberRepository::class)->create(['mobile'=>'09173011987']);
        $this->app->make(BlacklistedNumberRepository::class)->create(['mobile'=>'09173011987']);
    }

    /** @test */
    function incoming_short_message_detects_blacklisted_number()
    {
        $from = $mobile = '09161234567';
        $direction = INCOMING;
        factory(BlacklistedNumber::class)->create(compact('mobile'));
        $this->expectsEvents(BlacklistedNumberDetected::class);

        factory(ShortMessage::class)->create(compact('from', 'direction'));
    }

    /** @test */
    function outgoing_short_message_detects_blacklisted_number()
    {
        $to = $mobile = '09161234567';
        $direction = OUTGOING;
        factory(BlacklistedNumber::class)->create(compact('mobile'));
        $this->expectsEvents(BlacklistedNumberDetected::class);

        factory(ShortMessage::class)->create(compact('to', 'direction'));
    }

    /** @test */
    function incoming_short_message_validates_against_blacklisted_numbers()
    {
        $from = $mobile = '09161234567';
        $direction = INCOMING;
        factory(BlacklistedNumber::class)->create(compact('mobile'));

        $this->setExpectedException(BlacklistedNumberException::class);

        factory(ShortMessage::class)->create(compact('from', 'direction'));
    }

    /** @test */
    function outgoing_short_message_validates_against_blacklisted_numbers()
    {
        $to = $mobile = '09161234567';
        $direction = OUTGOING;
        factory(BlacklistedNumber::class)->create(compact('mobile'));

        $this->setExpectedException(BlacklistedNumberException::class);

        factory(ShortMessage::class)->create(compact('to', 'direction'));
    }
}
