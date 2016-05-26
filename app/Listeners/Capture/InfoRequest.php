<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\InfoRepository;
use App\Jobs\SendShortMessage;

class InfoRequest extends TextCommanderListener
{
    static protected $regex = "/(?<code>%s)\s?(?<arguments>.*)/i";

    private $infos;

    /**
     *
     * InfoRequest constructor.
     * @param $infos
     */
    public function __construct(InfoRepository $infos)
    {
        $this->infos = $infos->skipPresenter();
        $this->populateRegex();
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $info = $this->infos->findByCode($this->code)->first();
        if (!is_null($info))
        {
            $job = new SendShortMessage($this->mobile, $info->description);

            $this->dispatch($job);
        }
    }

    /**
     * Modify the regex, get all the codes in Info
     * and concatenate the result.
     *
     * @return void
     */
    protected function populateRegex()
    {
        $codes = implode('|', $this->infos->all()->pluck('code')->toArray());
        static::$regex = sprintf(static::$regex, $codes);
    }

}
