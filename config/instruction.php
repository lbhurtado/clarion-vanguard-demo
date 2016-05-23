<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 23/05/16
 * Time: 11:23
 */

return [
    'driver' => env('DEFAULT_SMS_DRIVER', 'log'),
    'from' => 'Your Number or Email',
    'telerivet' => [
        'api_key'    => env('TELERIVET_API_KEY'),
        'project_id' => env('TELERIVET_PROJECT_ID'),
    ],
    'join group' => [
        'match' => "/(?<group_alias>[^\s]+)\s?(?<contact_handle>.*)/i",
//        'job' => App\Jobs\JoinGroup::class,
        'alias' => 'group_alias',
        '--mobile' => 'automated this',
        '--leave' => false,
    ],
];
