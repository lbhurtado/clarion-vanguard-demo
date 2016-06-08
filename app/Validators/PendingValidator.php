<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class PendingValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
			'code' => 'required',
		],
        ValidatorInterface::RULE_UPDATE => [
			'code' => 'required',
		],
   ];

}