<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Group extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
        'alias'
	];

    protected $attributes = [
        'alias' => "asdsa",
    ];

    function contacts() {
        return $this->belongsToMany(Contact::class);
    }
}
