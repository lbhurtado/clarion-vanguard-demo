<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\ContactRepository;
use App\Validators\ContactValidator;
use App\Entities\Contact;
use App\Mobile;

/**
 * Class ContactRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Contact::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ContactValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Mobile field has to be formatted before searching.
     * @param $field
     * @param null $value
     * @param array $columns
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        if ($field == 'mobile')
        {
            $value = Mobile::number($value);
        }

        return parent::findByField($field, $value, $columns);
    }

    /**
     * Mobile field has to be formatted before updateOrCreate.
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $attributes['mobile'] = Mobile::number($attributes['mobile']);

        return parent::updateOrCreate($attributes, $values);
    }
}
