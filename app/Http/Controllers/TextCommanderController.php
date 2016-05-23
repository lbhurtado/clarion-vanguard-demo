<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TextCommanderController extends Controller
{
    function joinGroup($group, $mobile, $handle = null)
    {
        return "{$group} {$mobile} {$handle}";
    }
}
