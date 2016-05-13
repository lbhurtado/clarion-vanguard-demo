<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\PendingRepository;
use App\Repositories\GroupRepository;
use App\Entities\Contact;
use App\Entities\Group;
use App\Mobile;


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

    /** @test */
    function group_can_create_pending_messages()
    {
        $this->artisan('db:seed');
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $group = $groups->findByField('name','brods')->first();

        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->create();
        $group->contacts()->attach($contact1);
        $group->contacts()->attach($contact2);

        $message = 'request';
        $origin = '09173011987';
        $contact3 = factory(Contact::class)->create(['mobile' => $origin, 'handle' => "origin"]);

        $pendings = $this->app->make(PendingRepository::class)->skipPresenter();

        $token = $groups->generatePendingMessages($group, $message, $origin);

        $this->assertCount(2, $pendings->all());
        $pending1 = $pendings->find(1);
        $pending2 = $pendings->find(2);
        $this->seeInDatabase($pending1->getTable(), [
            'from' => Mobile::number($origin),
            'to' => Mobile::number($contact1->mobile),
            'message' => $message,
            'token' => $token
        ]);
        $this->seeInDatabase($pending2->getTable(), [
            'from' => Mobile::number($origin),
            'to' => Mobile::number($contact2->mobile),
            'message' => $message,
            'token' => $token
        ]);
    }
}
