<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Events\ShortMessageWasRecorded;
use App\Listeners\Capture\InfoRequest;
use App\Repositories\InfoRepository;
use App\Entities\ShortMessage;
use App\Entities\Info;
use App\Mobile;

class InfoRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function info_request_is_listening()
    {
        factory(Info::class)->create(['code' => 'info', 'description' => 'info description']);
        factory(Info::class)->create(['code' => 'about', 'description' => 'about description']);

        $this->expectsEvents(ShortMessageWasRecorded::class);

        $from = $mobile = '09173011987';
        $codes = $this->app->make(InfoRepository::class)->skipPresenter()->all()->pluck('code')->toArray();
        foreach ($codes as $code)
        {
            $message = "{$code} arguments";
            $short_message = factory(ShortMessage::class)->create(compact('from', 'message'));
            $listener = new InfoRequest(\App::make(InfoRepository::class));
            $listener->handle(new ShortMessageWasRecorded($short_message));

            $this->assertTrue($listener->regexMatches($attributes));
            $this->assertEquals($code, $attributes['command']);
        }

        $this->assertEquals(Mobile::number('09173011987'), $attributes['mobile']);
    }
}
