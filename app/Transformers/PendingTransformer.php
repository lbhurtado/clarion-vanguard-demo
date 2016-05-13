<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Pending;

/**
 * Class PendingTransformer
 * @package namespace App\Transformers;
 */
class PendingTransformer extends TransformerAbstract
{

    /**
     * Transform the \Pending entity
     * @param \Pending $model
     *
     * @return array
     */
    public function transform(Pending $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
