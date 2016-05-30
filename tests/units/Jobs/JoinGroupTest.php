<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use App\Jobs\JoinGroup;
use App\Mobile;

class JoinGroupTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function join_group_does_the_job()
    {
        $groups = $this->app->make(GroupRepository::class);
        $contacts = $this->app->make(ContactRepository::class);
        $name = $alias = 'vanguard';
        $group = $groups->create(compact('name','alias'));
        $mobile = '09173011987';
        $contact = $contacts->create(['mobile' => $mobile, 'handle' => "Lester '92"]);

        $this->assertCount(0, $groups->find($group->id)->contacts);

        $attributes = [
            'token' => $group->alias,
            'mobile' => $contact->mobile,
            'handle' => $contact->handle,
        ];

        $job = new JoinGroup($attributes);
        $this->dispatch($job);

        $this->assertCount(1, $groups->find($group->id)->contacts);

        $job = new JoinGroup($attributes);
        $this->dispatch($job);

        $this->assertCount(1, $groups->find($group->id)->contacts);

        $this->assertEquals(Mobile::number('09173011987'), $group->contacts->first()->mobile);
        $this->assertEquals("Lester '92", $group->contacts->first()->handle);
        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact->id
        ]);
    }
}
