<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\ContactRepository;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function contact_mobile_handle_fields()
    {
        $contact = $this->app->make(ContactRepository::class)->create([
            'mobile' => '09189362340',
        ]);

        $this->assertEquals('09189362340', $contact->mobile);
        $this->assertEquals('09189362340', $contact->handle);
    }
}
