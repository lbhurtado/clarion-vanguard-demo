<?php

namespace App\Repositories;

use App\Entities\Pending;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BroadcastRepository;
use App\Entities\Broadcast;
use App\Validators\BroadcastValidator;
use App\Repositories\PendingRepository;

/**
 * Class BroadcastRepositoryEloquent
 * @package namespace App\Repositories;
 */
class BroadcastRepositoryEloquent extends BaseRepository implements BroadcastRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Broadcast::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return BroadcastValidator::class;
    }

    public function createWithPending(Pending $pending, $attributes = [])
    {
        $attributes['pending_id'] = $pending->id;

        return $this->create($attributes);
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
