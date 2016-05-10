<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\ShortMessage;

/**
 * Class ShortMessageTransformer
 * @package namespace App\Transformers;
 */
class ShortMessageTransformer extends TransformerAbstract
{

    /**
     * Transform the \ShortMessage entity
     * @param \ShortMessage $model
     *
     * @return array
     */
    public function transform(ShortMessage $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
