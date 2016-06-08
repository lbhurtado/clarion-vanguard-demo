<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Pending;

class Broadcast extends Model implements Transformable
{
	use TransformableTrait, SoftDeletes;

    protected $fillable = [
		'pending_id',
		'from',
		'to',
		'message',
	];

	public function pending()
	{
		return $this->belongsTo(Pending::class);
	}
}
