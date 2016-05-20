<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class WhitelistedNumberValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'mobile'	=> 'required',
	],
        ValidatorInterface::RULE_UPDATE => [
            'mobile'	=> 'required',
        ],
   ];

}