<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\SubscriptionRepository;
use App\Entities\Subscription;
use League\Csv\Reader;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function subscription_has_code_description_fields()
    {
        $code = 'test';
        $description = 'Test Description';
        $info = factory(Subscription::class)->create(compact('code', 'description'));

        $this->assertEquals($code, $info->code);
        $this->assertEquals($description, $info->description);
    }

    /** @test */
    function subscription_has_required_code_and_description_fields()
    {
        $code = 'test';
        $description = 'Test Description';
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);
        $this->app->make(SubscriptionRepository::class)->create(compact('description'));
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);
        $this->app->make(SubscriptionRepository::class)->create(compact('code'));
    }

    /** @test */
    function subscription_has_unique_code_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $code = 'test';
        $description = 'Test Description 1';
        factory(Subscription::class)->create(compact('code', 'description'));

        $description = 'Test Description 2';
        factory(Subscription::class)->create(compact('code', 'description'));
    }

    /** @test */
    function subscription_can_be_seeded()
    {
        $this->artisan('db:seed', ['--class' => 'SubscriptionsTableSeeder']);
        $reader = Reader::createFromPath(database_path('subscriptions.csv'));
        $subscription = $this->app->make(SubscriptionRepository::class)->first();
        if (!is_null($subscription))
        {
            foreach ($reader as $index => $row)
            {
                $code = $row[0];
                $description = $row[1];
                $this->seeInDatabase($subscription->getTable(), compact('code', 'description'));
            }
        }
    }
}
