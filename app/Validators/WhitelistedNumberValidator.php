<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class WhitelistedNumberValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'mobile' => 'required|phone:AUTO,PH',
	],
        ValidatorInterface::RULE_UPDATE => [
            'mobile' => 'required|phone:AUTO,PH',
        ],
   ];

}