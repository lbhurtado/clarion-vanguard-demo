<?php

namespace App\Jobs;

use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\BlacklistedNumberDetected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use App\Mobile;

class RecordShortMessage extends Job
{
    private $from;

    private $to;

    private $message;

    private $direction;

    private $reader;

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

        $this->reader = Reader::createFromPath(database_path('blacklist.csv'));
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

        $short_messages->create($attributes);
//        in_array($from, Mobile::blacklist())
//            ? event(new BlacklistedNumberDetected($attributes))
//            : $short_messages->create($attributes);
    }

    /**
     * @return array
     */
    protected function getBlackList()
    {
        $numbers = [];
        foreach ($this->reader as $index => $row) {
            $numbers [] = $row[0];
        }
        return $numbers;
    }
}
