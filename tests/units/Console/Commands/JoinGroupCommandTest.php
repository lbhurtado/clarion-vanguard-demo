<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Criteria\MobileCriterion;

class JoinGroupCommandTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function join_group_command_works()
    {
        $groups = $this->app->make(GroupRepository::class);
        $contacts = $this->app->make(ContactRepository::class);
        $name = $code = 'brods';
        $group = $groups->create(compact('name','code'));
        $mobile1 = '09173011987';
        $contact1 = $contacts->create(['mobile' => $mobile1]);

        $this->assertCount(0, $groups->find($group->id)->contacts);

        $this->artisan('txtcmdr:group:join', [
                'code' => $code,
            '--mobile' => $contact1->mobile,
             '--leave' => false
        ]);

        $this->artisan('txtcmdr:group:join', [
                'code' => $code,
            '--mobile' => $contact1->mobile,
             '--leave' => false
        ]);

        $this->assertCount(1, $groups->find($group->id)->contacts);

        $this->seeInDatabase($group->contacts()->getTable(), [
              'group_id' => $group->id,
            'contact_id' => $contact1->id
        ]);

        $mobile2 = '09189362340';
        $contact2 = $contacts->create(['mobile' => $mobile2]);

        $this->artisan('txtcmdr:group:join', [
                'code' => $code,
            '--mobile' => $contact2->mobile,
             '--leave' => false
        ]);

        $this->assertCount(2, $groups->find($group->id)->contacts);

        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact2->id
        ]);

        $this->artisan('txtcmdr:group:join', [
                'code' => $code,
            '--mobile' => $mobile2,
             '--leave' => true
        ]);

        $this->artisan('txtcmdr:group:join', [
                'code' => $code,
            '--mobile' => $mobile2,
             '--leave' => true
        ]);

        $this->assertCount(1, $groups->find($group->id)->contacts);

        $this->notSeeInDatabase($group->contacts()->getTable(), [
              'group_id' => $group->id,
            'contact_id' => $contact2->id
        ]);
    }
}
