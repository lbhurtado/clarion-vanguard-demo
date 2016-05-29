<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Events\GroupMembershipsWereProcessed;
use App\Listeners\Capture\GroupMembership;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;
use App\Entities\Group;
use App\Entities\Token;
use App\Mobile;

class GroupMembershipTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function group_membership_is_listening()
    {
        $group1 = factory(Group::class)->create([
            'name' => 'UP Vanguard, Inc.',
            'alias' => 'vanguard'
        ]);

        Token::generate($group1, 'vanguard');

        $group2 = factory(Group::class)->create([
            'name' => 'UP Physics Association',
            'alias' => 'uppa'
        ]);

        Token::generate($group2, 'uppa');

        $short_message = factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'message'   => "uppa Lester '92",
            'direction' => INCOMING
        ]);

        $listener = $this->app->make(GroupMembership::class);
        $listener->handle(new ShortMessageWasRecorded($short_message));

        $this->assertTrue($listener->regexMatches($attributes));
        $this->assertEquals('uppa', $attributes['token']);
        $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
        $this->assertEquals("Lester '92", $attributes['handle']);

        $this->assertCount(1, $group2->contacts);

        $this->assertEquals(Mobile::number('09173011987'), $group2->contacts->first()->mobile);
        $this->assertEquals("Lester '92", $group2->contacts->first()->handle);
//        $this->seeInDatabase($group2->contacts()->getTable(), [
//            'group_id' => $group2->id,
//            'contact_id' => $contact->id
//        ]);
    }
}
