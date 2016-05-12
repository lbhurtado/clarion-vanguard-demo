<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CreateContactFromShortMessage;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use SimpleSoftwareIO\SMS\Facades\SMS;
use App\Jobs\JoinGroup;
use App\Mobile;

class JoinGroupTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function join_group_does_the_job()
    {
        $this->artisan('db:seed');

        factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'message'   => "vanguard Lester '92",
            'direction' => INCOMING
        ]);

        factory(ShortMessage::class)->create([
            'from'      => '09189362340',
            'message'   => "vanguard Buboy '91",
            'direction' => INCOMING
        ]);

//        $job = new JoinGroup($short_message);
//        $this->dispatch($job);

        $group = $this->app
            ->make(GroupRepository::class)
            ->skipPresenter()
            ->findWhere(['name' => 'brods'])
            ->first();

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact1 = $contacts->findWhere(['mobile' => Mobile::number('09173011987')])->first();
        $contact2 = $contacts->findWhere(['mobile' => Mobile::number('09189362340')])->first();

        $this->assertCount(2, $group->contacts);
        $this->assertEquals("Lester '92", $contact1->handle);
        $this->assertEquals("Buboy '91", $contact2->handle);

        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact1->id
        ]);
        $this->seeInDatabase($group->contacts()->getTable(), [
            'group_id' => $group->id,
            'contact_id' => $contact2->id
        ]);

        factory(ShortMessage::class)->create([
            'from'      => '09189362340',
            'message' => "brods, testing broadcast"
        ]);
    }
}
