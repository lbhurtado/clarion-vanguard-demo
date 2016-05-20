<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface WhitelistedNumberRepository
 * @package namespace App\Repositories;
 */
interface WhitelistedNumberRepository extends RepositoryInterface
{

    /**
     * @return boolean
     */
    public function enabled();

    /**
     * @param $mobile
     * @return boolean
     */
    public function negative($mobile);
}
