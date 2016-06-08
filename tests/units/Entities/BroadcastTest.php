<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\BroadcastRepository;
use App\Repositories\PendingRepository;
use App\Mobile;

class BroadcastTest extends TestCase
{
    use DatabaseMigrations;

    private $pendings;

    private $broadcasts;

    function setUp()
    {
        parent::setUp();

        $this->broadcasts = $this->app->make(BroadcastRepository::class)->skipPresenter();
        $this->pendings = $this->app->make(PendingRepository::class)->skipPresenter();
    }

    /** @test */
    function broadcast_has_from_to_message_fields()
    {
        $code = 'test';
        $pending = $this->pendings->create(compact('code'));
        list($from, $to, $message) = [Mobile::number('09189362340'), Mobile::number('09173011987'), 'Testing 123'];
        $attributes = compact('from', 'to', 'message');
        $broadcast = $this->broadcasts->createWithPending($pending, $attributes);
        $this->assertEquals($pending->code, $broadcast->pending->code);
        $this->assertEquals($from, $broadcast->from);
        $this->assertEquals($to, $broadcast->to);
        $this->assertEquals($message, $broadcast->message);
        $this->setExpectedException(Illuminate\Database\QueryException::class);
        $this->broadcasts->createWithPending($pending, $attributes);
    }
}
