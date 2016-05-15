<?php

namespace App\Jobs;

use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

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
     * @param ShortMessageRepository $shortMessageRepository
     */
    public function handle(ShortMessageRepository $shortMessageRepository)
    {
        $from = $this->from;
        $to = $this->to;
        $message = $this->message;
        $direction = $this->direction;

        $numbers = $this->getBlackList();

        if (in_array($from, $numbers))
        {
            throw new \Exception('test');
        }
        $shortMessageRepository->create(compact('from', 'to', 'message', 'direction'));
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
