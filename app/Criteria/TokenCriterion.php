<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 13/05/16
 * Time: 12:40
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class TokenCriterion implements CriteriaInterface
{

    private $token;

    /**
     * TokenCriterion constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $model = $model->where('token', '=', $this->token);

        return $model;
    }

}