<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\ShortMessageRepository;
use App\Repositories\ContactRepository;
use App\Repositories\PendingRepository;
use App\Mobile;

class POSTsmsRouteTest extends TestCase
{
    use DatabaseMigrations;

    private $short_messages;

    private $contacts;

    public function setUp()
    {
        parent::setUp();

        $this->short_messages = $this->app->make(ShortMessageRepository::class);
        $this->contacts = $this->app->make(ContactRepository::class);
    }

    /** @test */
    public function POST_sms_creates_contact()
    {
        $route = 'sms/09173011987/09189362340/asdsadas';
        for($i = 1; $i <= 3; $i++)
        {
            $this->call('POST', $route);
            $this->assertResponseOk();
        }

        $this->assertCount(3, $this->short_messages->skipPresenter()->all());
        $this->assertCount(1, $this->contacts->skipPresenter()->all());
        $contact = $this->contacts->findByField('mobile', '09173011987')->first();
        $this->assertEquals(Mobile::number('09173011987'), $contact->handle);
    }

    /** @test */
    public function POST_sms_contact_joins_group()
    {
        $this->artisan('db:seed');
        $route = "sms/09173011987/09189362340/vanguard Lester Hurtado '91";
        $this->call('POST', $route);

        $this->assertResponseOk();

        $contact = $this->contacts->findByField('mobile', '09173011987')->first();
        $this->assertEquals("Lester Hurtado '91", $contact->handle);

        $route = "sms/09189362340/09173011987/vanguard Buboy Lizaso '91";
        $this->call('POST', $route);

        $this->assertResponseOk();

        $contact = $this->contacts->findByField('mobile', '09189362340')->first();
        $this->assertEquals("Buboy Lizaso '91", $contact->handle);

        $route = "sms/09189362340/09173011987/vanguard Marcelino Lizaso Jr. '91";
        $this->call('POST', $route);

        $this->assertResponseOk();

        $contact = $this->contacts->findByField('mobile', '09189362340')->first();
        $this->assertEquals("Marcelino Lizaso Jr. '91", $contact->handle);
    }

    /** @test */
    public function POST_sms_sends_broadcast_from_mobile()
    {
        $this->artisan('db:seed');
        $route = "sms/09173011987/09189362340/vanguard Lester Hurtado '91";
        $this->call('POST', $route);

        $this->assertResponseOk();

        $route = "sms/09189362340/09173011987/vanguard Buboy Lizaso '91";
        $this->call('POST', $route);

        $this->assertResponseOk();

//        $route = "sms/09173011987/09189362340/brods All hail to thee, Vanguard Fraternity";
//        $this->call('POST', $route);
//
//        $this->assertResponseOk();
//        $pendings = $this->app->make(PendingRepository::class)->skipPresenter();
//        $this->assertCount(2, $pendings->all());
//
//        $route = "sms/09173011987/09189362340/APPROVE 1234";
//        $this->call('POST', $route);
//
//        $this->assertResponseOk();
//
//        $this->assertCount(0, $pendings->all());
    }
}
