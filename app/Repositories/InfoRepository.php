<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface InfoRepository
 * @package namespace App\Repositories;
 */
interface InfoRepository extends RepositoryInterface
{
    public function findByCode($code, $columns = ['*']);
}
