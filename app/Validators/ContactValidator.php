<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ContactValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'mobile'	=> 'required|phone:AUTO,PH',
            'handle'	=> 'min:1',
	],
        ValidatorInterface::RULE_UPDATE => [
			'mobile'	=> 'phone:AUTO,PH',
            'handle'	=> 'min:1',
		],
   ];

}