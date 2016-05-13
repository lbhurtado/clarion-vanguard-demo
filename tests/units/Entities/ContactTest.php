<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use App\Entities\Group;
use App\Mobile;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function contact_mobile_handle_fields()
    {
        $contact = $this->app->make(ContactRepository::class)->create([
            'mobile' => '09189362340',
        ]);

        $this->assertEquals(Mobile::number('09189362340'), $contact->mobile);
        $this->assertEquals(Mobile::number('09189362340'), $contact->handle);
    }


    /** @test */
    function contact_has_unique_mobile_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);
        factory(Contact::class)->create(['mobile' => '09173011987']);
        factory(Contact::class)->create(['mobile' => '09173011987']);
    }

    /** @test */
    function contact_has_a_factory()
    {
        $contact = factory(Contact::class)->create(['mobile' => '09189362340', 'handle' => 'Lester']);

        $this->assertEquals(Mobile::number('09189362340'), $contact->mobile);
        $this->assertEquals('Lester', $contact->handle);
    }

    /** @test */
    function contact_has_groups()
    {
        $contact = factory(Contact::class)->create();
        $group1 = factory(Group::class)->create();
        $group2 = factory(Group::class)->create();

        $contact->groups()->attach($group1);
        $contact->groups()->attach($group2);

        $this->assertCount(2, $contact->groups);
        $this->seeInDatabase($contact->groups()->getTable(), [
            'contact_id' => $contact->id,
            'group_id' => $group1->id
        ]);
        $this->seeInDatabase($contact->groups()->getTable(), [
            'contact_id' => $contact->id,
            'group_id' => $group2->id
        ]);
    }
}
