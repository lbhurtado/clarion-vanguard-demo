<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\TokenValidator;
use Illuminate\Support\Collection;
use App\Entities\Contact;
use App\Entities\Token;
use Closure;

/**
 * Class TokenRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TokenRepositoryEloquent extends BaseRepository implements TokenRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Token::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TokenValidator::class;
    }

    /**
     * Populates the contact_id in tokens tables
     * and soft deletes the record
     *
     * @param Contact $contact
     * @param Closure $callback
     * @param $code
     * @return mixed
     */
    function claim(Contact $contact, $code, Closure $callback = null)
    {
        $token = $this
            ->findByFieldCaseInsensitive('code', $code)
            ->first()
            ->conjureObject()
            ->claimed_by($contact);

        $object = $token->getObject();

        if (is_int($token->quota)){
            $token->quota = $token->quota - 1;
        }

        if ($token->quota === 0)
        {
            $token->delete();
        }

        if (is_null($callback))
            return $object;

        if (is_null($object))
            return null;

        return $callback($object);
    }

    /**
     * Generate tokens given a collection
     *
     * @param Collection $collection
     * @param $code
     * @param $quota
     * @return mixed
     */
    function generate(Collection $collection, $code = null, $quota = null)
    {
        $collection->each(function($item, $key) use ($code, $quota) {
            $this->create([
                'code'       => $code ?: str_random(6),
                'class'      => get_class($item),
                'reference'  => $item->id,
                'quota'      => $quota
            ]);
        });
    }

    /**
     * Generate 1-time tokens give a collection
     *
     * @param Collection $collection
     * @param null $code
     * @return mixed
     */
    function generateOneTime(Collection $collection, $code = null)
    {
        $this->generate($collection, $code, 1);
    }

    /**
     * @param $field
     * @param null $value
     * @param array $columns
     * @return mixed
     */
    public function findByFieldCaseInsensitive($field, $value = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
//        $value = preg_replace('/\s+/', '', strtoupper($value));
        $value = strtoupper(trim($value));
        $model = $this->model->whereRaw("UPPER($field) = ?", [$value])->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
