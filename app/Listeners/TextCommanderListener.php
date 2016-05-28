<?php

namespace App\Listeners;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;

abstract class TextCommanderListener
{
    use DispatchesJobs;

    protected $event;

    protected $attributes;

    protected $repository;

    protected $regex; // "/(?<token>{put class here})\s?(?<handle>.*)/i";

    protected $column;

    protected $mappings = [
        'attributes' =>
            [
                'token'  => 'token',
                'mobile' => 'mobile',
                'handle' => 'handle'
            ],
    ];

    /**
     * @param mixed $event
     */
    protected function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * Check if the message has the same pattern as the $this->regex.
     * Visibility is made public for testing purposes only.
     * &$matches parameter for testing purposes only.
     *
     * @param $matches
     * @return bool
     */
    public function regexMatches(&$matches = null)
    {
        if (preg_match($this->regex, $this->event->shortMessage->message, $matches))
        {
            $m = [];

            foreach ($matches as $k => $v)
            {
                if (is_int($k))
                {
                    unset($matches[$k]);
                }
                else
                {
                  $m[$this->mappings['attributes'][$k]] = $v;
                }
            }
            $m = $this->insertMobile($m);

            $matches = $this->insertMobile($matches);

//            $this->attributes = $matches;
            $this->attributes = $m;

            return true;
        }

        return false;
    }

    /**
     * Insert the mobile into the $matches because
     * the mobile field is inherent to the msg.
     *
     * @param $matches
     * @return mixed
     */
    private function insertMobile(&$matches)
    {
        $matches [$this->mappings['attributes']['mobile']] = $this->event->shortMessage->mobile;

        return $matches;
    }

    /**
     * Handle the event. Execute if regexMatches.
     * Save the event just in case it is
     * needed by other sub-classes.
     *
     * @param ShortMessageWasRecorded $event
     * @throws \Exception
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $this->setEvent($event);

        if (is_null($this->repository))
            throw new \Exception('$this->repository cannot be null');

        if (is_null($this->regex))
            throw new \Exception('$this->regex cannot be null');

        if (is_null($this->column))
            throw new \Exception('$this->column cannot be null');

        $this->processRegex($this->regex, $this->column);

        if ($this->regexMatches())
        {
            return $this->execute();
        }
    }

    /**
     * Placeholders for keywords from the database are
     * placed in curly brackets {} in $this->regex.
     * Depending on the tags, table and column.
     *
     * @param &$regex
     * @param $column
     * @throws \Exception
     * @return void
     */
    protected function processRegex(&$regex, $column)
    {
        if (preg_match_all("/<(?<tags>[^>]*)>/", $regex, $matches)) {
            $tags = $matches['tags'];

            if (array_key_exists(0, $tags)) {
                switch ($tags[0]) {
                    case 'token':
                        if (!isset($this->repository))
                            throw new \Exception('The $this->repository cannot be null if token tag is present!');
                        if (!isset($column))
                            throw new \Exception('The $column cannot be null if token tag is present!');
                        $keywords = implode('|', $this->repository->all()->pluck($column)->toArray());
                        $regex = preg_replace("/{(?<class>[^}]*)}/i", $keywords, $regex);

                        break;
                }
            }

            if (array_key_exists(1, $tags)) {
                switch ($tags[1]) {
                    case 'handle':

                        break;
                }
            }
        }
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    abstract protected function execute();
}
