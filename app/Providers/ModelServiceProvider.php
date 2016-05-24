<?php

namespace App\Providers;

use App\Events\WhitelistedNumberNotDetected;
use App\Events\BlacklistedNumberDetected;
use Illuminate\Support\ServiceProvider;
use App\Events\ShortMessageWasRecorded;
use App\Entities\BlacklistedNumber;
use App\Entities\WhitelistedNumber;
use App\Events\ContactWasCreated;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Pending;
use App\Entities\Group;
use App\Mobile;

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

        ShortMessage::creating(function ($model) {
            $model->from = Mobile::number($model->from);
            $model->to   = Mobile::number($model->to);

            if (WhitelistedNumber::enabled())
                if (WhitelistedNumber::negative($model->mobile))
                    event(new WhitelistedNumberNotDetected($model->attributes));

            if (BlacklistedNumber::positive($model->mobile))
                event(new BlacklistedNumberDetected($model->attributes));
        });

        ShortMessage::created(function ($model) {
            $this->app->instance(ShortMessage::class, $model);
            event(new ShortMessageWasRecorded($model));
        });

        Contact::creating(function ($model) {
            $model->mobile = Mobile::number($model->mobile);
            $model->handle = $model->handle ?: $model->mobile;
        });

        Contact::created(function ($model) {
            event(new ContactWasCreated($model));
        });

        Group::creating(function ($model) {
            $model->alias = $model->alias ?: $model->name;
            $model->alias = strtolower($model->alias);
        });

        Group::updating(function ($model) {
            $model->alias = $model->alias ?: $model->name;
            $model->alias = strtolower($model->alias);
        });

        Pending::creating(function ($model) {
            $model->from = Mobile::number($model->from);
            $model->to   = Mobile::number($model->to);
        });

        BlacklistedNumber::creating(function($model){
            $model->mobile = Mobile::number($model->mobile);
        });

        WhitelistedNumber::creating(function($model){
            $model->mobile = Mobile::number($model->mobile);
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
