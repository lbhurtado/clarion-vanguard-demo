<?php

namespace App\Listeners;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;

abstract class TextCommanderListener
{
    use DispatchesJobs;

    static protected $regex;

    private $message;

    private $mobile;

    protected $event;

    protected $attributes;

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
        if (preg_match(static::$regex, $this->message, $matches))
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
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $this->setEvent($event);
        if ($this->regexMatches())
        {
            $this->execute();
        }
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    abstract protected function execute();
}
