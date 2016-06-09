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

    function contacts() {
        return $this->belongsToMany(Contact::class);
    }

    function parent()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    function groups() {
        return $this->hasMany(Group::class, 'parent_id');
    }
}
