<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\WhitelistedNumber;

/**
 * Class WhitelistedNumberTransformer
 * @package namespace App\Transformers;
 */
class WhitelistedNumberTransformer extends TransformerAbstract
{

    /**
     * Transform the \WhitelistedNumber entity
     * @param \WhitelistedNumber $model
     *
     * @return array
     */
    public function transform(WhitelistedNumber $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
