<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Entities\Group;
/**
 * Interface GroupRepository
 * @package namespace App\Repositories;
 */
interface GroupRepository extends RepositoryInterface
{
    public function findByCode($code);

    public function generatePendingMessages(Group $group, $message, $origin);
}
