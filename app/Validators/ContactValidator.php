<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ContactValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
		'moble'	=>'	required',
		'handle'	=>'	required',
	],
        ValidatorInterface::RULE_UPDATE => [
		'moble'	=>'	required',
		'handle'	=>'	required',
	],
   ];

}