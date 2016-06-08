<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\PendingRepository;
use App\Entities\Contact;
use App\Entities\Pending;

class PendingTest extends TestCase
{
    use DatabaseMigrations;

    private $pendings;

    function setUp()
    {
        parent::setUp();

        $this->pendings = $this->app->make(PendingRepository::class)->skipPresenter();
    }

    /** @test */
    function pending_has_a_unique_code_field()
    {
        $code = 'test';
        $pending = $this->pendings->create(compact('code'));
        $this->assertEquals($code, $pending->code);
        $this->setExpectedException(Illuminate\Database\QueryException::class);
        $this->pendings->create(compact('code'));
    }

    /** @test */
    function pending_has_a_contact()
    {
        $pending = factory(Pending::class)->create();
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->create();

        $pending->contact()->associate($contact1);
        $pending->save();

        $pending->contact()->associate($contact2);
        $pending->save();

        $this->assertEquals($contact2, $pending->contact);
    }

    /** @test */
    function pending_has_broadcasts()
    {
        $code = 'test';
        $pending = $this->pendings->create(compact('code'));
    }
}
