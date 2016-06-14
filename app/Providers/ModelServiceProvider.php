<?php

namespace App\Providers;

use App\Events\WhitelistedNumberNotDetected;
use App\Events\BlacklistedNumberDetected;
use App\Listeners\Capture\BroadcastApproved;
use Illuminate\Support\ServiceProvider;
use App\Events\ShortMessageWasRecorded;
use App\Entities\BlacklistedNumber;
use App\Entities\WhitelistedNumber;
use App\Events\ContactWasCreated;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Broadcast;
use App\Entities\Group;
use App\Entities\Info;
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
            $model->code = $model->code ?: $model->name;
            $model->code = str_slug($model->code);
//            $model->code = strtolower($model->code);
        });

        Group::updating(function ($model) {
            $model->code = $model->code ?: $model->name;
            $model->code = strtolower($model->code);
        });

        Broadcast::creating(function ($model) {
            $model->from = Mobile::number($model->from);
            $model->to   = Mobile::number($model->to);
        });

        BlacklistedNumber::creating(function($model){
            $model->mobile = Mobile::number($model->mobile);
        });

        WhitelistedNumber::creating(function($model){
            $model->mobile = Mobile::number($model->mobile);
        });

        Info::creating(function ($model) {
            $model->code = strtolower($model->code);
        });

        Info::updating(function ($model) {
            $model->code = strtolower($model->code);
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
