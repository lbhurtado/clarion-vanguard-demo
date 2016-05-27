<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;
use App\Entities\Contact;
use Closure;

/**
 * Interface TokenRepository
 * @package namespace App\Repositories;
 */
interface TokenRepository extends RepositoryInterface
{
    /**
     * @param Contact $contact
     * @param Closure $callback
     * @param $code
     * @return mixed
     */
    function claim(Contact $contact, $code, Closure $callback = null);

    /**
     * Generate tokens given a collection
     *
     * @param Collection $collection
     * @param $code
     * @param $quota
     * @return mixed
     */
    function generate(Collection $collection, $code = null, $quota = null);

    /**
     * Generate 1-time tokens give a collection
     *
     * @param Collection $collection
     * @param null $code
     * @return mixed
     */
    function generateOneTime(Collection $collection, $code = null);

    /**
     * @param $field
     * @param null $value
     * @param array $columns
     * @return mixed
     */
    public function findByFieldCaseInsensitive($field, $value = null, $columns = ['*']);
}
