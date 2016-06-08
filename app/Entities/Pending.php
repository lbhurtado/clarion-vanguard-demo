<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Broadcast;

class Pending extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = [
		'code',
	];

	function contact()
	{
		return $this->belongsTo(Contact::class);
	}

	function broadcasts()
	{
		return $this->hasMany(Broadcast::class);
	}
}
