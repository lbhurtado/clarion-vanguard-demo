<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\InfoRepository;
use App\Entities\Info;
use App\Validators\InfoValidator;

/**
 * Class InfoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class InfoRepositoryEloquent extends BaseRepository implements InfoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Info::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return InfoValidator::class;
    }

    public function findByCode($code, $columns = ['*'])
    {
        return $this->findByField('code', strtolower($code), $columns);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
