<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Broadcast;

/**
 * Class BroadcastTransformer
 * @package namespace App\Transformers;
 */
class BroadcastTransformer extends TransformerAbstract
{

    /**
     * Transform the \Broadcast entity
     * @param \Broadcast $model
     *
     * @return array
     */
    public function transform(Broadcast $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
