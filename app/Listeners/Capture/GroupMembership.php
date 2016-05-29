<?php

namespace App\Listeners\Capture;

use App\Listeners\TextCommanderListener;
use App\Repositories\ContactRepository;
use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Jobs\JoinGroup;

class GroupMembership extends TextCommanderListener
{
    protected $regex = "/(?<token>{App\Entities\Group})\s?(?<handle>.*)/i";

    protected $column = 'alias';

    private $tokens;

    private $contacts;

    protected $mappings = [
        'attributes' => [
            'token'  => 'keyword',
        ],
    ];

    /**
     * @param GroupRepository $repository
     * @param TokenRepository $tokens
     * @param ContactRepository $contacts
     */
    public function __construct(GroupRepository $repository, TokenRepository $tokens, ContactRepository $contacts)
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
        $token = $this->attributes['keyword'];

        $contact = $this->contacts->findByField('mobile', $this->attributes['mobile'])->first();
        if (!is_null($contact))
        {
            $this->tokens->claim($contact, $token, function($group) use ($contact) {
                $job = new JoinGroup($this->attributes);
                $this->dispatch($job);
            });
        }

    }
}
