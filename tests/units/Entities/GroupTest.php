<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\BroadcastRepository;
use App\Repositories\PendingRepository;
use App\Repositories\GroupRepository;
use App\Criteria\PendingCodeCriterion;
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
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $group = $groups->create(['name' => 'UP Vanguard', 'alias' => 'vanguard']);
        $cnt = 3;
        for ($i=1;$i<=$cnt;$i++)
        {
            $group->contacts()->attach(factory(Contact::class)->create());
        }
        $message = 'group can create pending messages';
        $origin = Mobile::number('09173011987');

        $code = $groups->generatePendingMessages($group, $message, $origin);
        $pending_broadcasts = $this->app->make(BroadcastRepository::class)->skipPresenter();
        $this->assertCount($cnt, $pending_broadcasts->getByCriteria(new PendingCodeCriterion($code)));
        $pending_broadcast = $pending_broadcasts->find(1);

        $this->seeInDatabase($pending_broadcast->getTable(), [
            'pending_id' => $pending_broadcast->pending->id,
                  'from' => $origin,
               'message' => $message,
        ]);
    }
}
