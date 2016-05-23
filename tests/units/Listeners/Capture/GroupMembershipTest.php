<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Listeners\Capture\GroupMembership;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;
use App\Entities\Group;
use App\Mobile;

class GroupMembershipTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function group_membership_is_listening()
    {
        factory(Group::class)->create([
            'name' => 'UP Vanguard, Inc.',
            'alias' => 'vanguard'
        ]);

        $short_message = factory(ShortMessage::class)->create([
            'from'      => '09173011987',
            'message'   => "vanguard Lester '92",
            'direction' => INCOMING
        ]);

        $listener = new GroupMembership();

        $listener->handle(new ShortMessageWasRecorded($short_message));

        $this->assertTrue($listener->regexMatches($attributes));
        $this->assertEquals('vanguard', $attributes['group_alias']);
        $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
        $this->assertEquals("Lester '92", $attributes['handle']);
    }
}
