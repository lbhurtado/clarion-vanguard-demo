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

    /**
     * This column is the field name of the table
     * to be used for concatenation of keywords
     * in the regex.
     * @var
     */
    protected $column;

    protected $defaults = [
        'attributes' =>
            [
                'token'  => 'token',
                'mobile' => 'mobile',
                'handle' => 'handle'
            ],
    ];

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
            foreach ($matches as $k => $v)
            {
                if (is_int($k))
                {
                    unset($matches[$k]);
                }
                else
                {
                    $this->setAttribute($this->attributes, $k, $v);
                }
            }
            $this->setAttribute($matches, 'mobile', $this->event->shortMessage->mobile); //for testing purposes
            $this->setAttribute($this->attributes, 'mobile', $this->event->shortMessage->mobile);

            return true;
        }

        return false;
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
        $this->event = $event;

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
                        $keywords = implode('|', $this->repository->all()->pluck($column)->unique()->toArray());
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

    /**
     * @param $k
     * @param $v
     */
    protected function setAttribute(&$ar, $k, $v)
    {
        if (array_key_exists($k, $this->mappings['attributes']))
        {
            $ar[$this->mappings['attributes'][$k]] = $v;
        }
        elseif (array_key_exists($k, $this->defaults['attributes']))
        {
            $ar[$this->defaults['attributes'][$k]] = $v;
        }
    }
}
