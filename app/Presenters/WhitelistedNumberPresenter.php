<?php

namespace App\Presenters;

use App\Transformers\WhitelistedNumberTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class WhitelistedNumberPresenter
 *
 * @package namespace App\Presenters;
 */
class WhitelistedNumberPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WhitelistedNumberTransformer();
    }
}
