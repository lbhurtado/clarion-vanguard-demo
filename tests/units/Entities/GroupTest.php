<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\GroupRepository;
use App\Entities\Contact;
use App\Entities\Group;


class GroupTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function group_has_default_values_from_seed()
    {
        $this->artisan('db:seed');

        $groups = $this->app->make(GroupRepository::class);

        $this->assertTrue(count($groups->all()) > 0);
    }

    /** @test */
    function group_has_a_factory()
    {
        $group = factory(Group::class)->create(['name' => 'secretariat']);

        $this->assertEquals('secretariat', $group->name);
    }

    /** @test */
    function group_has_contacts()
    {
        $group = factory(Group::class)->create();
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->create();

        $group->contacts()->attach($contact1);
        $group->contacts()->attach($contact2);

        $this->assertCount(2, $group->contacts);
        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact1->id
        ]);
        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact2->id
        ]);
    }
}
