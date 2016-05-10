<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ShortMessageRepository;
use App\Entities\ShortMessage;
use App\Validators\ShortMessageValidator;

/**
 * Class ShortMessageRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ShortMessageRepositoryEloquent extends BaseRepository implements ShortMessageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShortMessage::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ShortMessageValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
