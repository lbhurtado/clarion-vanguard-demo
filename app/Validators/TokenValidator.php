<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class TokenValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
			'code'	=> 'required',
			'class'	=> 'required',
			'reference'	=> 'required|integer',
			'quota' => 'integer|min:0',
		],
        ValidatorInterface::RULE_UPDATE => [
			'code'	=> 'required',
			'class'	=> 'required',
			'reference'	=> 'required|integer',
			'quota' => 'integer|min:0',
		],
   ];

}