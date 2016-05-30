<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
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
        $token = $name = $alias = 'vanguard';
        $group = $groups->create(compact('name','alias'));
        $mobile = Mobile::number('09173011987');
        $handle =  "Lester '91";
        $contact = $contacts->create(compact('mobile', 'handle'));
        $this->assertCount(0, $groups->find($group->id)->contacts);
        $attributes = compact('token', 'mobile', 'handle');
        for ($i = 0; $i <= 5; $i++)
        {
            $job = new JoinGroup($attributes);
            $this->dispatch($job);
        }
        $this->assertCount(1, $groups->find($group->id)->contacts);
        $this->assertEquals($mobile, $group->contacts->first()->mobile);
        $this->assertEquals($handle, $group->contacts->first()->handle);
        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact->id
        ]);
    }
}
