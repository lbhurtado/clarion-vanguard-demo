<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class GroupValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|unique:groups,name,null',
            'code' => 'unique:groups,code,null',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
        ],
   ];

}