<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BlacklistedNumberRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use App\Entities\BlacklistedNumber;
use App\Validators\BlacklistedNumberValidator;
use App\Criteria\MobileCriterion;

/**
 * Class BlacklistedNumberRepositoryEloquent
 * @package namespace App\Repositories;
 */
class BlacklistedNumberRepositoryEloquent extends BaseRepository implements BlacklistedNumberRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BlacklistedNumber::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return BlacklistedNumberValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public function positive($mobile)
    {
        return $this->getByCriteria(new MobileCriterion($mobile))->count() > 0;
    }
}
