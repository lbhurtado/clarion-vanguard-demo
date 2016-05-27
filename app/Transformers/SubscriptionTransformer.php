<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Subscription;

/**
 * Class SubscriptionTransformer
 * @package namespace App\Transformers;
 */
class SubscriptionTransformer extends TransformerAbstract
{

    /**
     * Transform the \Subscription entity
     * @param \Subscription $model
     *
     * @return array
     */
    public function transform(Subscription $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
