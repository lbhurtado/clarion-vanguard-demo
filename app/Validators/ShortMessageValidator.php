<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ShortMessageValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'message' => 'required',
            'from'	  => 'phone:AUTO,PH',
            'to'	  => 'phone:AUTO,PH',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'message' => 'required',
            'from'	  => 'phone:AUTO,PH',
            'to'	  => 'phone:AUTO,PH',
        ],
   ];

}