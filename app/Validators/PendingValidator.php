<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class PendingValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
		'from'	=>'	required',
		'to'	=>'	required',
		'message'	=>'	required',
		'token'	=>'	required',
	],
        ValidatorInterface::RULE_UPDATE => [
		'from'	=>'	required',
		'to'	=>'	required',
		'message'	=>'	required',
		'token'	=>'	required',
	],
   ];

}