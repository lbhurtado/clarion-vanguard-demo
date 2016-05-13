<?php

namespace App\Jobs;

use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\SMS\Facades\SMS;
use App\Jobs\Job;
use App\Mobile;

class SendShortMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $mobile;

    private $message;

    /**
     * SendShortMessage constructor.
     * @param $mobile
     * @param $message
     */
    public function __construct($mobile, $message)
    {
        $this->mobile = $mobile;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mobile = Mobile::national($this->mobile);
        SMS::send($this->message, [], function($sms) use ($mobile) {
            $sms->to($mobile);
            \App::make(ShortMessageRepository::class)->skipPresenter()->create([
                'from'      => '09178251991',
                'to'        => $mobile,
                'message'   => $this->message,
                'direction' => OUTGOING
            ]);
        });
    }
}
