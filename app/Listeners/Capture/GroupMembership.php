<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\JoinGroup;
use App\Instruction;

class GroupMembership
{
    use DispatchesJobs;

    static protected $regex = "/(?<group_alias>[^\s]+)\s?(?<handle>.*)/i";

    private $message;

    private $mobile;

    /**
     * @return mixed
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    public function regexMatches(&$matches)
    {
        if (preg_match(static::$regex, $this->message, $matches))
        {
            foreach($matches as $k => $v)
            {
                if(is_int($k))
                {
                    unset($matches[$k]);
                }
            }
            $matches = $this->insertMobile($matches);

            return true;
        }

        return false;
    }

    /**
     * @param $matches
     * @return mixed
     */
    protected function insertMobile(&$matches)
    {
        $matches ['mobile'] = $this->mobile;
        return $matches;
    }

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
//        $this->instruction = $event->shortMessage->getInstruction();
//
//        $instruction = $event->shortMessage->getInstruction();
//
//        if ($instruction->isValid())
//        {
//            switch ($instruction->getKeyword())
//            {
//                case strtoupper(Instruction::$keywords['REGISTRATION']):
//                    $job = new JoinGroup($instruction->getKeyword(), $event->shortMessage->mobile, $instruction->getArguments());
//                    $this->dispatch($job);
//                    break;
//            }
//        }

        $this->message = $event->shortMessage->message;
        $this->mobile = $event->shortMessage->mobile;
        if ($this->regexMatches($attributes))
        {
            $job = new JoinGroup($attributes);
            $this->dispatch($job);
        }
    }


}
