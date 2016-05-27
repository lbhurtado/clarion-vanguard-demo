<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class TokenValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
		'code'	=>'	required',
		'class'	=>'	required',
		'reference'	=>'	integer',
	],
        ValidatorInterface::RULE_UPDATE => [
		'code'	=>'	required',
		'class'	=>'	required',
		'reference'	=>'	integer',
	],
   ];

}