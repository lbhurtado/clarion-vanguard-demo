<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 31/05/16
 * Time: 22:16
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Entities\Pending;

class PendingCodeCriterion implements CriteriaInterface
{
    private $code;

    /**
     * PendingCodeCriterion constructor.
     * @param $pending
     */
    public function __construct($code)
    {
        $this->code = $code;
    }


    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->with('pending')->whereHas('pending', function($q) {
            $q->where('code', '=', $this->code);
        });

        return $model;
    }

}