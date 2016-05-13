<?php

namespace App\Http\Controllers;

use App\Jobs\RecordShortMessage;
use Illuminate\Http\Request;
use App\Http\Requests;

class SMSController extends Controller
{
    private $request;

    /**
     * SMSController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sms($from, $to, $message)
    {
        $job = new RecordShortMessage($from, $to, $message, INCOMING);
        $this->dispatch($job);

        return compact('from', 'to', 'message');
    }
}
