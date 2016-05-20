<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use App\Repositories\BlacklistedNumberRepository;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class BlacklistedNumber extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'mobile',
	];

    public static function positive($mobile)
    {
        return \App::make(BlacklistedNumberRepository::class)->positive($mobile);
    }
}
