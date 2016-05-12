<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

define('INCOMING', -1);
define('OUTGOING',  1);

class ShortMessage extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'from',
		'to',
		'message',
		'direction'
	];

	protected $attributes = [
		'direction' => INCOMING,
	];

}
