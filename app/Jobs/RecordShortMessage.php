<?php

namespace App\Jobs;

use App\Repositories\ShortMessageRepository;
use App\Entities\ShortMessage;

class RecordShortMessage extends Job
{
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
     * @param ShortMessageRepository $short_messages
     */
    public function handle(ShortMessageRepository $short_messages)
    {
        $from = $this->from;
        $to = $this->to;
        $message = $this->message;
        $direction = $this->direction;
        $attributes = compact('from', 'to', 'message', 'direction');

        $short_message = $short_messages->create($attributes);
        \App::instance(ShortMessage::class, $short_message);
    }
}
