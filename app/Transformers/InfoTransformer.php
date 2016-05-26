<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Info;

/**
 * Class InfoTransformer
 * @package namespace App\Transformers;
 */
class InfoTransformer extends TransformerAbstract
{

    /**
     * Transform the \Info entity
     * @param \Info $model
     *
     * @return array
     */
    public function transform(Info $model)
    {
        return [
            'id'         => (int) $model->id,
            'code'       => $model->code,
            'description'=> $model->description,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
