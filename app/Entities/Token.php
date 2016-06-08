<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\PendingRepository;

class Token extends Model implements Transformable
{
	use TransformableTrait, SoftDeletes;

	private $object;

	protected $fillable = [
		'code',
		'class',
		'reference',
		'quota'
	];

	protected $casts = [
		'reference' => 'integer',
		'quota' => 'integer'
	];

	protected $dates = ['deleted_at'];

	public function claimer()
	{
		return $this->belongsTo(Contact::class);
	}

	/**
	 * Automates the association of contacts
	 *
	 * @param Contact $contact
	 * @return $this
	 */
	public function claimed_by(Contact $contact)
	{
		$this->claimer()->associate($contact);

		return $this;
	}

	/**
	 * Instantiate the class
	 * when given the id
	 *
	 * @return $this
	 */
	public function conjureObject()
	{
		try
		{
			$this->object = \App::make($this->class)->find($this->reference);
		}
		catch (\Exception $e)
		{
			dd($this->class);
		}


		return $this;
	}

	public function getObject()
	{
		return $this->object;
	}

	public static function generate(Model $model, $code = null, $quota = null)
	{
		$code = $code ?: str_random(6);
		$class = get_class($model);
		$reference = $model->id;

		return static::create(compact('code', 'class', 'reference', 'quota'));
	}

	public static function generateOneTime(Model $model, $code = null)
	{
		return static::generate($model, $code, 1);
	}

	public static function generatePending($code = null)
	{
		$code = $code ?: str_random(6);
		$pending = \App::make(PendingRepository::class)->skipPresenter()->create(compact('code'));

		return static::generateOneTime($pending, $code);
	}
}
