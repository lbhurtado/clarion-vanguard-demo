<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use App\Repositories\WhitelistedNumberRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\WhitelistedNumberValidator;
use App\Entities\WhitelistedNumber;
use App\Criteria\MobileCriterion;

/**
 * Class WhitelistedNumberRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WhitelistedNumberRepositoryEloquent extends BaseRepository implements WhitelistedNumberRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WhitelistedNumber::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return WhitelistedNumberValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return boolean
     */
    public function enabled()
    {
        return $this->paginate(1, ['mobile'])->count() > 0;
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public function negative($mobile)
    {
        return $this->getByCriteria(new MobileCriterion($mobile))->count() == 0;
    }
}
