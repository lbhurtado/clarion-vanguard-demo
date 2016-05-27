<?php

namespace App\Listeners;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;

abstract class TextCommanderListener
{
    use DispatchesJobs;

//    static protected $regex;

    private $message;

    private $mobile;

    protected $event;

    protected $attributes;

    protected $repository;

    protected $regex = "/(?<command>%s)\s?(?<arguments>.*)/i";

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
        $this->message = $event->shortMessage->message;
        $this->mobile = $event->shortMessage->mobile;
    }

    /**
     * Check if the message has the same pattern as the static::$regex.
     * Visibility is made public for testing purposes only.
     * &$matches parameter for testing purposes only.
     *
     * @param $matches
     * @return bool
     */
    public function regexMatches(&$matches = null)
    {
        if (preg_match($this->regex, $this->message, $matches))
        {
            foreach ($matches as $k => $v)
            {
                if (is_int($k))
                {
                    unset($matches[$k]);
                }
            }
            $matches = $this->insertMobile($matches);

            $this->attributes = $matches;

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
        $matches ['mobile'] = $this->mobile;

        return $matches;
    }


    /**
     * Magic method to get individual variables from $this->attributes
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes))
        {
            return $this->attributes[$name];
        }
    }

    /**
     * Handle the event. And
     * pass the attributes.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return mixed
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $this->setEvent($event);
        if ($this->regexMatches())
        {
            return $this->execute();
        }
    }

    /**
     * Modify the regex, get all the columns in Model
     * and concatenate the result.
     *
     * @param string $column
     */
    protected function populateRegex($column = 'code')
    {
        $keywords = implode('|', $this->repository->all()->pluck($column)->toArray());
//        static::$regex = sprintf(static::$regex, $keywords);
        $this->regex = sprintf($this->regex, $keywords);
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    abstract protected function execute();
}
