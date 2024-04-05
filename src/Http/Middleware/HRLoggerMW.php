<?php

namespace Fadiramzi99\HrLogger\Http\Middleware;

use Closure;
use Exception;
use Fadiramzi99\HrLogger\Helpers\PayloadFilter;
use Fadiramzi99\HrLogger\Helpers\TimeUnit;
use Illuminate\Http\Request;
use Fadiramzi99\HrLogger\Models\HRLog;
use Illuminate\Support\Str;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Log;

class HRLoggerMW
{
    protected TimeUnit $timeUnit;
    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->timeUnit = new TimeUnit(config('hr-logger.execution_time_unit'));
    }

    public function handle(Request $request, Closure $next)
    {
        if (config('hr-logger.logging_enabled')) {
            $this->timeUnit->start();
            // Extract information from the request
            $endpoint = $request->path();
            $controllerAction = $request->route()->getAction('controller');
            list($controller, $method) = explode('@', $controllerAction);
           
            
            if(count(config('hr-logger.sensitive_keys',[])) > 0)
            {
                $payload = PayloadFilter::excludeSensitiveKeys($request->all(), config('hr-logger.sensitive_keys'));
            }
          
            $requestID = (string) Str::uuid();
            $sourceIP = $request->ip();
            $userAgent = $request->userAgent();
            $apiVersion = 'v1';
            $httpMethod = $request->method();
            $requestTime = now();
            $executionTime = 0; // Placeholder for now

            $timeUnit = config('hr-logger.execution_time_unit');
            $hrLog = null;
            try {
                // Store information in the database

                $hrLog = HRLog::create([
                    'request_id' => Str::uuid(),
                    'source_ip' =>  $sourceIP,
                    'user_agent' => $userAgent,
                    'api_version' => $apiVersion,
                    'endpoint' => $endpoint,
                    'controller' => $controller,
                    'method_name' => $method,
                    'http_method' => $httpMethod,
                    'request_time' => $requestTime,
                    'execution_time' =>  $executionTime, // Placeholder for now
                    'time_unit' => $timeUnit, // 's' or 'ms
                    'request_payload' => $payload,
                ]);
                if ($hrLog) {
                    $response = $next($request);
                    // Update the record with response details
                    $this->timeUnit->end();

                    $hrLog->update([
                        'response_time' => now(),
                        'response_payload' => $response->getContent(),
                        'response_code' => $response->getStatusCode(),
                        'execution_time' => $this->timeUnit->getExecutionTime(),
                        'time_unit' => $timeUnit, // 's' or 'ms

                        'exception' => $response->exception ?? '',
                    ]);
                    return $response;
                }

                throw new Exception('HRLog_not_created');
            } catch (Exception $exception) {

                $this->timeUnit->end();
                $hrLog = HRLog::create([
                    'request_id' => $requestID,
                    'source_ip' =>  $sourceIP,
                    'user_agent' => $userAgent,
                    'api_version' => $apiVersion,
                    'endpoint' => $endpoint,
                    'controller' => $controller,
                    'method_name' => $method,
                    'http_method' => $httpMethod,
                    'request_time' => $requestTime,
                    'execution_time' =>  $this->timeUnit->getExecutionTime(), // Placeholder for now
                    'request_payload' => $payload,
                    'response_time' => now(),
                    'response_payload' => '',
                    'response_code' => 500,
                    'exception' => $exception->getMessage(),
                ]);



                // Rethrow the exception to be handled by Laravel's exception handler
                throw $exception;
            }
        }
    }
}
