<?php

namespace App\Listeners\Capture;

use App\Listeners\TextCommanderListener;
use App\Repositories\InfoRepository;
use App\Jobs\SendShortMessage;

class InfoRequest extends TextCommanderListener
{
    protected $regex = "/(?<token>{App\Entities\Info})\s?(?<arguments>.*)/i";

    protected $column = 'code';

    protected $mappings = [
        'attributes' => [
            'token'  => 'keyword',
        ],
    ];

    /**
     *
     * InfoRequest constructor.
     * @param $repository
     */
    public function __construct(InfoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $info = $this->repository->findByCode($this->attributes['keyword'])->first();
        if (!is_null($info))
        {
            $job = new SendShortMessage($this->attributes['mobile'], $info->description);
            $this->dispatch($job);
        }
    }

}
