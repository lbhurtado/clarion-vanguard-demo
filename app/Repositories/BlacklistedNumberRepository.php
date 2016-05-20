<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BlacklistedNumberRepository
 * @package namespace App\Repositories;
 */
interface BlacklistedNumberRepository extends RepositoryInterface
{
    /**
     * @param $mobile
     * @return mixed
     */
    public function positive($mobile);
}
