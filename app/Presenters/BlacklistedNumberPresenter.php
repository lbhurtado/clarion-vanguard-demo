<?php

namespace App\Presenters;

use App\Transformers\BlacklistedNumberTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BlacklistedNumberPresenter
 *
 * @package namespace App\Presenters;
 */
class BlacklistedNumberPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new BlacklistedNumberTransformer();
    }
}
