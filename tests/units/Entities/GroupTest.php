<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Prettus\Validator\Exceptions\ValidatorException;
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
    function group_has_a_name_and_code()
    {
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $name = 'Test 1';
        $code = 'test1';
        $group = $groups->create(compact('name', 'code'));

        $this->assertEquals($name, $group->name);
        $this->assertEquals($code, $group->code);
        $this->seeInDatabase($group->getTable(), [
            'code' => $group->code,
            'name' => $group->name
        ]);
    }

    /** @test */
    function group_has_auto_slug_code()
    {
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $name = 'Test 1';
        $group = $groups->create(compact('name'));

        $this->assertEquals(str_slug($name), $group->code);
    }

    /** @test */
    function group_has_a_unique_name()
    {
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $name = 'Test 1';
        $groups->create(compact('name'));
        $this->setExpectedException(ValidatorException::class);
        $groups->create(compact('name'));
    }

    /** @test */
    function group_has_a_unique_code()
    {
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $name = 'Test 1';
        $code = 'test1';
        $groups->create(compact('name', 'code'));
        $this->setExpectedException(ValidatorException::class);
        $groups->create(compact('code'));
    }


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
        $group = $groups->create(['name' => 'UP Vanguard', 'code' => 'vanguard']);
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

    /** @test */
    function group_has_a_parent()
    {
        $parent = factory(Group::class)->create();
        $group1 = factory(Group::class)->create();
        $group2 = factory(Group::class)->create();
        $group3 = factory(Group::class)->create();
        $parent->groups()->save($group1);
        $parent->groups()->save($group2);
        $group3->parent()->associate($parent)->save();
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();

        $this->assertEquals($parent->id, $group1->parent->id);
        $this->seeInDatabase($parent->getTable(), [
                   'id' => $group1->id,
            'parent_id' => $parent->id
        ]);
        $this->seeInDatabase($parent->getTable(), [
                   'id' => $group2->id,
            'parent_id' => $parent->id
        ]);
    }

    /** @test */
    function group_has_unique_code_and_name_fields_under_same_parent_group()
    {
        $parent = factory(Group::class)->create();
        $name = 'Group 1';
        $child1 = factory(Group::class)->create(compact('name'));
        $child2 = factory(Group::class)->create(compact('name'));
        $parent->groups()->save($child1);
        $this->setExpectedException(Illuminate\Database\QueryException::class);
        $parent->groups()->save($child2);
    }

    /** @test */
    function group_has_dot_notation()
    {
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $this->assertCount(0, $groups->all());
        $group = 'grandson.child1.parent';
        $this->artisan('txtcmdr:group:conjure', compact('group'));
        $this->assertCount(3, $groups->all());
        $group = 'grandson.child2.parent';
        $this->artisan('txtcmdr:group:conjure', compact('group'));
        $this->assertCount(4, $groups->all());

//        dd($groups->all()->toArray());
//        dd($groups->find(2)->lineage);
    }
}
