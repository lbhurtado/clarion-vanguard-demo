<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\PendingRepository;
use App\Validators\PendingValidator;
use App\Presenters\PendingPresenter;
use App\Entities\Pending;

/**
 * Class PendingRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PendingRepositoryEloquent extends BaseRepository implements PendingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Pending::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return PendingValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return PendingPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
