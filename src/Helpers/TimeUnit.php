<?php

namespace Fadiramzi99\HrLogger\Helpers;

class TimeUnit
{
    private $startTime;
    private $endTime;
    private $timeUnit;

    const TIME_UNIT_SECOND = 's';
    const TIME_UNIT_MILLISECOND = 'ms';
    public function __construct($timeUnit = self::TIME_UNIT_SECOND)
    {
        $this->timeUnit = $timeUnit;
    }

    public function start()
    {
        $this->startTime = microtime(true);
    }

    public function end()
    {
        $this->endTime = microtime(true);
    }

    public function getExecutionTime()
    {
        $executionTime = $this->endTime - $this->startTime;

        switch ($this->timeUnit) {
            case self::TIME_UNIT_MILLISECOND:
                return $executionTime * 1000;
            case self::TIME_UNIT_SECOND:
                return $executionTime;
            default:
                return $executionTime;
        }
    }
}