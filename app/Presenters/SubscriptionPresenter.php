<?php

namespace App\Presenters;

use App\Transformers\SubscriptionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class SubscriptionPresenter
 *
 * @package namespace App\Presenters;
 */
class SubscriptionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SubscriptionTransformer();
    }
}
