<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 20/05/16
 * Time: 17:40
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class MobileCriterion implements CriteriaInterface
{
    private $mobile;
    private $field;

    /**
     * @param $mobile
     * @param string $field
     */
    public function __construct($mobile, $field = 'mobile')
    {
        $this->mobile = $mobile;
        $this->field = $field;
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
        $model = $model->where($this->field, '=', $this->mobile);

        return $model;
    }

}