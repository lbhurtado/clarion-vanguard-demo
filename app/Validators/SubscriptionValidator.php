<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class SubscriptionValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
			'code'	=> 'required',
			'description'	=> 'required',
		],
        ValidatorInterface::RULE_UPDATE => [
			'code'	=> 'required',
			'description'	=> 'required',
		],
   ];

}