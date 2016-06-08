<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Events\ShortMessageWasRecorded;
use App\Listeners\Capture\InfoRequest;
use App\Repositories\InfoRepository;
use App\Listeners\Capture\Contact as CaptureContactListener;
use App\Entities\ShortMessage;
use App\Entities\Token;
use App\Entities\Info;
use App\Mobile;

class InfoRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function info_request_is_listening()
    {
        $info1 = factory(Info::class)->create(['code' => 'info1', 'description' => 'info1 description']);
        Token::generate($info1, $info1->code);

        $info2 = factory(Info::class)->create(['code' => 'info2', 'description' => 'info2 description']);
        Token::generate($info2, $info2->code);

        $this->expectsEvents(ShortMessageWasRecorded::class);

        $from = $mobile = Mobile::number('09173011987');
        $codes = $this->app->make(InfoRepository::class)->skipPresenter()->all()->pluck('code')->toArray();
        foreach ($codes as $code)
        {
            $message = "{$code} arguments";
            $short_message = factory(ShortMessage::class)->create(compact('from', 'message'));
            $capture_contact_listener = $this->app->make(CaptureContactListener::class);
            $event = new ShortMessageWasRecorded($short_message);
            $capture_contact_listener->handle($event);

            $info_request_listener = $this->app->make(InfoRequest::class);
            $info = $info_request_listener->handle($event);
            $this->assertTrue($info_request_listener->regexMatches($attributes));
            $this->assertEquals($code, $info->code);
        }

        $this->assertEquals($mobile, $attributes['mobile']);
    }
}
