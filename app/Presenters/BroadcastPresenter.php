<?php

namespace App\Presenters;

use App\Transformers\BroadcastTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BroadcastPresenter
 *
 * @package namespace App\Presenters;
 */
class BroadcastPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new BroadcastTransformer();
    }
}
