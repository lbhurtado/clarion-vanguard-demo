<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Entities\Contact;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Contact::creating(function ($model) {
            $model->mobile = $model->mobile;
            $model->handle = $model->handle ?: $model->mobile;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
