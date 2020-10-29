<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\v1\Log;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function save_log($user_type, $access_token_or_phone_or_email, $log_title, $log_description)
    {
        $log = new Log();
        $log->user_type = $user_type; 
        $log->user_id_or_phone_or_email = $access_token_or_phone_or_email;
        $log->log_title = $log_title; 
        $log->log_description = $log_description;
        $log->save();

    }
}
