<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = [
		'from',
		'to',
		'message',
		'token',
	];

}
