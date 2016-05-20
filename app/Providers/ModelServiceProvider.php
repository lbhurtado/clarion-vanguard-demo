<?php

namespace App\Providers;

use App\Repositories\BlacklistedNumberRepository;
use App\Events\BlacklistedNumberDetected;
use Illuminate\Support\ServiceProvider;
use App\Events\ShortMessageWasRecorded;
use App\Entities\BlacklistedNumber;
use App\Events\ContactWasCreated;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Pending;
use App\Mobile;
//use App\Entities\BlacklistedNumber;

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

//            if ($this->numberIsBlacklisted($model->mobile))
            if (BlacklistedNumber::positive($model->mobile))
                event(new BlacklistedNumberDetected($model->attributes));
        });

        ShortMessage::created(function ($model) {
            event(new ShortMessageWasRecorded($model));
        });

        Contact::creating(function ($model) {
            $model->mobile = Mobile::number($model->mobile);
            $model->handle = $model->handle ?: $model->mobile;
        });

        Contact::created(function ($model) {
            event(new ContactWasCreated($model));
        });

        Pending::creating(function ($model) {
            $model->from = Mobile::number($model->from);
            $model->to   = Mobile::number($model->to);
        });

        BlacklistedNumber::creating(function($model){
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

    private function numberIsBlacklisted($mobile)
    {
        $blacklisted_numbers = $this->app->make(BlacklistedNumberRepository::class);

        return $blacklisted_numbers->check($mobile);
    }
}
