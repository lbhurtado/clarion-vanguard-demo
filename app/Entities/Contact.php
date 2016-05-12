<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Contact extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'mobile',
		'handle',
	];

	public function groups()
	{
		return $this->belongsToMany(Group::class);
	}

}
