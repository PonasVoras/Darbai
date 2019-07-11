<?php

namespace Operations;

class ExecutionCalculator
{
    private $startTime = 0.0;
    private $endTime = 0.0;

    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    public function end(): void
    {
        $this->endTime = microtime(true);
    }

    public function executionTime(): float
    {
        return $this->endTime - $this->startTime;
    }
}