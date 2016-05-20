<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use App\Repositories\WhitelistedNumberRepository;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class WhitelistedNumber extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'mobile',
	];

    public static function enabled()
    {
        return \App::make(WhitelistedNumberRepository::class)->enabled();
    }

    public static function negative($mobile)
    {
        return \App::make(WhitelistedNumberRepository::class)->negative($mobile);
    }
}
