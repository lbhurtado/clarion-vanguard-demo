<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Entities\Pending;

/**
 * Interface BroadcastRepository
 * @package namespace App\Repositories;
 */
interface BroadcastRepository extends RepositoryInterface
{
    public function createWithPending(Pending $pending, $attributes = []);
}
