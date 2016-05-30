<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CreateContactFromShortMessage;
use App\Repositories\ContactRepository;
use App\Events\ShortMessageWasRecorded;
use App\Criteria\MobileCriterion;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Mobile;

class CreateContactFromMessageTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function create_contact_from_message_does_the_job()
    {
        $this->expectsEvents(ShortMessageWasRecorded::class); // to suppress the listeners
        $mobile = $from = Mobile::number('09173011987');
        $short_message = factory(ShortMessage::class)->create(compact('from'));
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $contact = $this->app->make(ContactRepository::class)
            ->getByCriteria(new MobileCriterion($mobile))
            ->first();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($short_message->contact, $contact);
        $this->assertEquals($mobile, $contact->mobile);
        $this->seeInDatabase($contact->getTable(), compact('mobile'));
    }
}
