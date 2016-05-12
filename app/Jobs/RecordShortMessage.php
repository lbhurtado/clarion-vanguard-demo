<?php

namespace App\Jobs;

use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordShortMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $from;

    private $to;

    private $message;

    private $direction;

    /**
     * RecordShortMessage constructor.
     * @param $from
     * @param $to
     * @param $message
     * @param $direction
     */
    public function __construct($from, $to, $message, $direction)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
        $this->direction = $direction;
    }

    /**
     * Execute the job.
     * @param ShortMessageRepository $shortMessageRepository
     */
    public function handle(ShortMessageRepository $shortMessageRepository)
    {
        $from = $this->from;
        $to = $this->to;
        $message = $this->message;
        $direction = $this->direction;

        $shortMessageRepository->create(compact('from', 'to', 'message', 'direction'));
    }
}
