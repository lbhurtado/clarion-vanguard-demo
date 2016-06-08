<?php

namespace App\Listeners\Capture;

use App\Listeners\TextCommanderListener;
use App\Repositories\ContactRepository;
use App\Repositories\TokenRepository;
use App\Repositories\InfoRepository;
use App\Jobs\SendShortMessage;

class InfoRequest extends TextCommanderListener
{
    public static $i = 0;
    protected $regex = "/(?<token>{App\Entities\Info})\s?(?<arguments>.*)/i";

    protected $column = 'code';

    private $tokens;

    private $contacts;

    protected $mappings = [
        'attributes' => [
            'token'  => 'token',
        ],
    ];

    /**
     * @param InfoRepository $repository
     * @param TokenRepository $tokens
     * @param ContactRepository $contacts
     */
    public function __construct(InfoRepository $repository, TokenRepository $tokens, ContactRepository $contacts)
    {
        $this->repository = $repository;
        $this->tokens = $tokens;
        $this->contacts = $contacts;
    }

    /**
     * Handle the event if regex matches.
     *
     * @return void
     */
    protected function execute()
    {
        $token = $this->attributes['token'];
        $contact = $this->contacts->findByField('mobile', $this->attributes['mobile'])->first();
        if (!is_null($contact))
        {
            $info =  $this->tokens->claim($contact, $token, function($info) use ($token, $contact) {
//                $job = new SendShortMessage($contact->mobile, $info->description);
//                $this->dispatch($job);

                return $info;
            });

            return $info;
        }
    }

}
