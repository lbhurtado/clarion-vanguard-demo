<?php

namespace App\Console\Commands;

use App\Repositories\GroupRepository;
use Illuminate\Console\Command;

class ConjureGroup extends Command
{
    private $groups;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'txtcmdr:group:conjure {group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param GroupRepository $groups
     */

    public function __construct(GroupRepository $groups)
    {
        parent::__construct();

        $this->groups = $groups->skipPresenter();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->argument('group');
        if ($this->is_valid_domain_name($domain))
        {
            $tokens = explode('.', $domain);
            $parent = null;
            while ( $name = array_pop( $tokens ) )
            {
//                $name = $parent ? "{$name}.{$parent->alias}" : $name;
                $group = $this->groups->updateOrCreate(compact('name'));
                if ($group)
                {
                    if ($parent)
                        $group->parent()->associate($parent)->save();
                    $parent = $group;
                }
            }

        }

        $this->info("Alias is {$domain}.");
    }

    function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }
}
