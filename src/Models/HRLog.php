<?php

namespace Fadiramzi99\HrLogger\Models;

use Illuminate\Database\Eloquent\Model;

class HRLog extends Model
{
    protected $table = 'hr_logs';

    protected $fillable = [
        // Define the fillable attributes here
        'request_id',
        'source_ip',
        'user_agent',
        'api_version',
        'endpoint',
        'controller',
        'method_name',
        'http_method',
        'request_time',
        'response_time',
        'execution_time',
        'time_unit',
        'request_payload',
        'response_payload',
        'response_code',
        'user_identifier',
        'exception',
    ];

    protected $guarded = [
        // Define the guarded attributes here
    ];

    // Define any relationships or additional methods here
}
