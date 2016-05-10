<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ShortMessageValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
		'message'	=>'	required',
	],
        ValidatorInterface::RULE_UPDATE => [
		'message'	=>'	required',
	],
   ];

}