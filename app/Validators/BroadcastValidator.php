<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class BroadcastValidator extends LaravelValidator {

    protected $rules = [
		ValidatorInterface::RULE_CREATE => [
			'pending_id' => 'required|integer|min:1',
			'from'	 	 => 'phone:AUTO,PH',
			'to'	  	 => 'required|phone:AUTO,PH',
			'message' 	 => 'required',
		],
		ValidatorInterface::RULE_UPDATE => [
			'pending_id' => 'required|integer|min:1',
			'from'	 	 => 'phone:AUTO,PH',
			'to'	  	 => 'required|phone:AUTO,PH',
			'message' 	 => 'required',
		],
   ];

}