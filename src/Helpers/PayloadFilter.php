<?php

namespace Fadiramzi99\HrLogger\Helpers;

class PayloadFilter
{
    public static function excludeSensitiveKeys($payload, $sensitiveKeys = [])
    {
       
        if(!$payload)
            return [];
        // Iterate over each key-value pair in the payload
        foreach ($payload as $key => &$value) {
            // If the value is an array or object, recursively call excludeSensitiveKeys
            if (is_array($value) || is_object($value)) {
                $value = self::excludeSensitiveKeys($value);
            }

            // If the key is a sensitive key, unset it
            if (in_array($key, $sensitiveKeys)) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }

}