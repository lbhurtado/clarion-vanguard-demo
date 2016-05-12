<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use Illuminate\Queue\SerializesModels;
use App\Entities\ShortMessage;
use App\Jobs\Job;

class CreateContactFromShortMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $shortMessage;

    /**
     * @param ShortMessage $shortMessage
     */
    public function __construct(ShortMessage $shortMessage)
    {
       $this->shortMessage = $shortMessage;
    }

    /**
     * @param ContactRepository $contacts
     */
    public function handle(ContactRepository $contacts)
    {
        $mobile = $this->shortMessage->mobile;

        $contacts->updateOrCreate(compact('mobile'), $this->shortMessage->attributesToArray());
    }
}
