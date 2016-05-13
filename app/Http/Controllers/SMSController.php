<?php

namespace App\Http\Controllers;

use App\Jobs\RecordShortMessage;
use App\Jobs\SendShortMessage;
use Illuminate\Http\Request;
use League\Csv\Reader;
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

    public function sun()
    {
        $to = "09229990758";
        $from = $this->request->get('from');
        $message = $this->request->get('msg');

        $this->sms($from, $to, $message);
    }

    public function broadcast()
    {
        $message = $this->request->get('msg');
        $reader = Reader::createFromPath(database_path('contacts.csv'));

        foreach ($reader as $index => $row)
        {
            $mobile = $row[0];
            $job = new SendShortMessage($mobile,$message);

            $this->dispatch($job);
        }

        return $message;
    }
}
