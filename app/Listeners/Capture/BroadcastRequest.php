<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Listeners\TextCommanderListener;
use App\Repositories\GroupRepository;

class BroadcastRequest extends TextCommanderListener
{
    protected $regex = "/(?:(?:broadcast|send)\s*|@)(?<token>{App\Entities\Group})\s*(?<message>.*)/i";

    protected $column = 'code';

    protected $mappings = [
        'attributes' => [
            'token'   => 'keyword',
            'message' => 'message'
        ],
    ];

    /**
     * @param GroupRepository $repository
     */
    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository->skipPresenter();
    }

    /**
     * Handle the event if regex matches.
     *
     * @return string
     */
    protected function execute()
    {
        $group = $this->repository->findByCode($this->attributes['keyword'])->first();

        return $this->repository->generatePendingMessages($group, $this->attributes['message'], $this->attributes['mobile']);
    }
}
