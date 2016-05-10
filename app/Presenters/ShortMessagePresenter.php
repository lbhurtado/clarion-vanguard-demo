<?php

namespace App\Presenters;

use App\Transformers\ShortMessageTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ShortMessagePresenter
 *
 * @package namespace App\Presenters;
 */
class ShortMessagePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ShortMessageTransformer();
    }
}
