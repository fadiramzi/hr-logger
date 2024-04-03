<?php

namespace Fadiramzi99\HrLogger\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Fadiramzi99\HrLogger\Models\HRLog;
use Illuminate\Support\Str;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Log;

class HRLoggerMW
{
    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }
    
    public function handle(Request $request, Closure $next)
    {
        if (config('hr-logger.logging_enabled')) {
            $startTime = microtime(true);
            // Extract information from the request
            $endpoint = $request->path();
            $payload = $request->all(); // Adjust as needed

            try {
                // Store information in the database
                $hrLog = HRLog::create([
                    'request_id' => (string) Str::uuid(),
                    'source_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'api_version' => $request->header('api-version'),
                    'endpoint' => $endpoint,
                    'controller' => $request->route()->getAction('controller'),
                    'method_name' => $request->route()->getAction('method'),
                    'http_method' => $request->method(),
                    'request_time' => now(),
                    'request_payload' => $payload,
                ]);

                $response = $next($request);

                // Update the record with response details
                $hrLog->update([
                    'response_time' => now(),
                    'response_payload' => $response->getContent(),
                    'response_code' => $response->getStatusCode(),
                    'execution_time' => microtime(true) - $startTime,
                    'exception' => $response->exception ?? '',
                ]);

                return $response;
            } catch (Exception $exception) {
              
                // Store the exception in the database
                $hrLog = HRLog::create([
                    'request_id' => (string) Str::uuid(),
                    'source_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'api_version' => $request->header('api-version'),
                    'endpoint' => $endpoint,
                    'controller' => $request->route()->getAction('controller'),
                    'method_name' => $request->route()->getAction('method'),
                    'http_method' => $request->method(),
                    'request_time' => now(),
                    'request_payload' => $payload,
                    'exception' => $exception->getMessage(),
                ]);

                // Rethrow the exception to be handled by Laravel's exception handler
                throw $exception;
            }
        }

    }
}
