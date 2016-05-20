<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\BlacklistedNumber;

/**
 * Class BlacklistedNumberTransformer
 * @package namespace App\Transformers;
 */
class BlacklistedNumberTransformer extends TransformerAbstract
{

    /**
     * Transform the \BlacklistedNumber entity
     * @param \BlacklistedNumber $model
     *
     * @return array
     */
    public function transform(BlacklistedNumber $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
