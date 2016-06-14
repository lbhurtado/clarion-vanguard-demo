<?php

namespace App\Console\Commands;

use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Criteria\MobileCriterion;
use Illuminate\Console\Command;
use App\Mobile;

class JoinGroup extends Command
{
    private $groups;

    private $contacts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'txtcmdr:group:join {code} {--mobile=} {--leave}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Join a group command';


    /**
     * @param GroupRepository $groups
     * @param ContactRepository $contacts
     */
    public function __construct(GroupRepository $groups, ContactRepository $contacts)
    {
        parent::__construct();

        $this->groups = $groups->skipPresenter();
        $this->contacts = $contacts->skipPresenter();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->argument('code');
        $group = $this->groups->findByCode($code)->first();

        if (!is_null($group))
        {
            $mobile = $this->option('mobile');
            $contact = $this->contacts->getByCriteria(new MobileCriterion($mobile))->first();
            if (!is_null($contact))
            {
                $leaving = $this->option('leave');
                if ($leaving)
                {
                    $group->contacts()->detach($contact);
                    $this->info("{$contact->handle} detached from {$group->name}.");
                }
                else
                {
                    $count_of_contacts_in_group = $group->with('contacts')->whereHas('contacts', function($q) use ($mobile) {
                        $q->where('mobile','=', Mobile::number($mobile));
                    })->count();

                    if ($count_of_contacts_in_group == 0)
                    {
                        $group->contacts()->attach($contact->id);
                        $this->info("{$contact->handle} attached to {$group->name}.");
                    }
                }
            }
        }
    }
}
