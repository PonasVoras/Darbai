<?php


namespace Log;

use DateTime;
use Operations\File;
use Log\Interfaces\LoggerInterface;
use InvalidArgumentException;

class Logger implements LoggerInterface
{

    private $fileName = "oop/Log/Log.log";
    private $logToFile = true;

    public function setLogToFile(bool $logToFile)
    {
        $this->logToFile = $logToFile;
    }

    public function emergency(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::EMERGENCY, $message, $context);
        $this->writeToFile($message);
    }

    public function alert(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::ALERT, $message, $context);
        $this->writeToFile($message);
    }

    public function critical(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::CRITICAL, $message, $context);
        $this->writeToFile($message);
    }

    public function error(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::ERROR, $message, $context);
        $this->writeToFile($message);
    }

    public function warning(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::WARNING, $message, $context);
        $this->writeToFile($message);
    }

    public function info(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::INFO, $message, $context);
        $this->writeToFile($message);
    }

    public function debug(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLvl::DEBUG, $message, $context);
        $this->writeToFile($message);
    }

    public function log(string $level, $message, array $context = array()): void
    {
        switch ($level) {
            case LogLvl::EMERGENCY:
                $this->emergency($message, $context);
                break;
            case LogLvl::ALERT:
                $this->alert($message, $context);
                break;
            case LogLvl::CRITICAL:
                $this->critical($message, $context);
                break;
            case LogLvl::ERROR:
                $this->error($message, $context);
                break;
            case LogLvl::WARNING:
                $this->warning($message, $context);
                break;
            case LogLvl::INFO:
                $this->info($message, $context);
                break;
            case LogLvl::DEBUG:
                $this->debug($message, $context);
                break;
            default:
                throw new InvalidArgumentException('Bad Log Level');
                break;
        }
    }

    private function writeToFile(string $message): void
    {
        if ($this->logToFile) {
            File::writeToFileString($this->fileName, $message);
        }
    }

    private function makeMessage(string $level, string $message, array $context = array())
    {
        $levelUpperCase = strtoupper($level);
        $dateTimeStr = (new DateTime())->format('Y-m-d H:i:s,u');
        $message = $this->interpolate($message, $context);
        return "$dateTimeStr [$levelUpperCase]: $message\n";
    }

    private function interpolate(string $message, array $context = array()): string
    {
        foreach ($context as $key => $value) {
            print_r($context);
            if (!is_array($value) && !is_object($value) || method_exists($value, '__toString')) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }
        return $message;
    }


}