<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\PendingRepository;
use App\Repositories\GroupRepository;
use App\Events\BroadcastWasRequested;

use App\Validators\GroupValidator;
use App\Entities\Group;

/**
 * Class GroupRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return GroupValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function generatePendingMessages(Group $group, $message, $origin)
    {
        $pendings = \App::make(PendingRepository::class)->skipPresenter();
        $token = "1234"; //mt_rand(1000, 9999);
        foreach($group->contacts as $contact)
        {
            $pendings->create([
                'from' => $origin,
                'to' => $contact->mobile,
                'message' => $message,
                'token' => $token
            ]);
        }
        event(new BroadcastWasRequested($group, $message, $origin, $token));

        return $token;
    }
}
