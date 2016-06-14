<?php

namespace App\Repositories;

use App\Entities\Pending;
use App\Entities\Token;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\BroadcastRepository;
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

    public function findByCode($code, $columns = ['*'])
    {
        return $this->findByField('code', strtolower($code), $columns);
    }

    public function generatePendingMessages(Group $group, $message, $origin)
    {
        $broadcast = \App::make(BroadcastRepository::class)->skipPresenter();
        $token = Token::generatePending();
        $pending = $token->conjureObject()->getObject();
        foreach($group->contacts as $contact)
        {
            $broadcast->createWithPending(
                $pending,
                [
                    'from'    => $origin,
                    'to'      => $contact->mobile,
                    'message' => $message,
                ]
            );
        }

//        event(new BroadcastWasRequested($group, $message, $origin, $token));

        return $token->code;
    }
}
