<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ShortMessageRepository;
use App\Jobs\CreateContactFromShortMessage;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\ContactRepository;
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

    /** @test */
    function short_message_has_a_calculated_mobile_attribute()
    {
        $short_message = factory(ShortMessage::class)->create(['from' => '09173011987', 'direction' => INCOMING]);

        $this->assertEquals(Mobile::number('09173011987 '), $short_message->mobile);

        $short_message = factory(ShortMessage::class)->create(['to' => '09173011987', 'direction' => OUTGOING]);

        $this->assertEquals(Mobile::number('09173011987 '), $short_message->mobile);

    }

    /** @test */
    function short_message_creates_a_create_contact_from_short_message_job()
    {
        $this->expectsJobs(CreateContactFromShortMessage::class);

        factory(ShortMessage::class)->create();
    }

    /** @test */
    function short_message_create_a_contact()
    {
        factory(ShortMessage::class)->create(['from' => '09173011987', 'direction' => INCOMING]);

        $contact = $this->app->make(ContactRepository::class)
            ->skipPresenter()
            ->findByField('mobile', Mobile::number('09173011987'))
            ->first();

        $this->assertEquals(Mobile::number('09173011987'), $contact->mobile);
        $this->assertEquals(Mobile::number('09173011987'), $contact->handle);
    }

    /** @test */
    function short_message_has_instructions()
    {
//        $this->artisan('db:seed');
        $short_message = factory(ShortMessage::class)->create(['message' => 'brods please ...']);

//        $this->assertEquals('brods', strtolower($short_message->getInstruction()->getKeyword()));
    }
}
