<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'code',
		'description',
	];

	function contacts() {
		return $this->belongsToMany(Contact::class);
	}
}
