<?php

namespace App\Listeners\Capture;

use App\Listeners\TextCommanderListener;
use App\Repositories\InfoRepository;
use App\Jobs\SendShortMessage;

class InfoRequest extends TextCommanderListener
{
    /**
     *
     * InfoRequest constructor.
     * @param $infos
     */
    public function __construct(InfoRepository $infos)
    {
        $this->repository = $infos->skipPresenter();
        $this->populateRegex('code');
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $info = $this->repository->findByCode($this->command)->first();
        if (!is_null($info))
        {
            $job = new SendShortMessage($this->mobile, $info->description);
            $this->dispatch($job);
        }
    }

}
