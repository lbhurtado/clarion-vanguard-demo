<?php

namespace App\Presenters;

use App\Transformers\PendingTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PendingPresenter
 *
 * @package namespace App\Presenters;
 */
class PendingPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PendingTransformer();
    }
}
