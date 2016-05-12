<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use App\Instruction;

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

	protected $instruction;

	private function getSignificantMobile(Array $attributes)
	{
		return $attributes['direction'] == INCOMING ? $attributes['from'] : $attributes['to'];
	}

	public function getMobileAttribute()
	{
		return $this->getSignificantMobile($this->attributes);
	}

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);

		$this->instruction = Instruction::create($this);
	}

	public function getInstruction()
	{
		return $this->instruction;
	}
}
