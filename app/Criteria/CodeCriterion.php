<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 25/05/16
 * Time: 23:26
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class CodeCriterion implements CriteriaInterface
{
    private $code;

    /**
     * CodeCriterion constructor.
     * @param $code
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
        $model = $model->where('code', '=', $this->code);

        return $model;
    }

}