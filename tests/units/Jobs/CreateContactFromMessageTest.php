<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CreateContactFromShortMessage;
use App\Repositories\ContactRepository;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Mobile;

class CreateContactFromMessageTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function create_contact_from_message_does_the_job()
    {
        factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'direction' => INCOMING
        ]);

//        $job = new CreateContactFromShortMessage($short_message);
//        $this->dispatch($job);

        $contact = $this->app
            ->make(ContactRepository::class)
            ->skipPresenter()
            ->findWhere(['mobile' => Mobile::number('09173011987')])
            ->first();

        $this->assertInstanceOf(Contact::class,  $contact);
        $this->assertEquals(Mobile::number('09173011987'),  $contact->mobile);
        $this->assertEquals(Mobile::number('09173011987'),  $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile' => Mobile::number('09173011987'),
            'handle' => Mobile::number('09173011987'),
        ]);
    }
}
