<?php


namespace Log;

use DateTime;
use InvalidArgumentException;
use Log\Interfaces\LoggerInterface;
use Operations\File;

class Logger implements LoggerInterface
{

    private $fileName = "oop/Log/Log.log";
    private $logToFile = true;
    private $colorFormats = array(
        // foreground colors
        'black' => "\033[30m%s\033[0m",
        'red' => "\033[31m%s\033[0m",
        'green' => "\033[32m%s\033[0m",
        'yellow' => "\033[33m%s\033[0m",
        'blue' => "\033[34m%s\033[0m",
        'magenta' => "\033[35m%s\033[0m",
        'cyan' => "\033[36m%s\033[0m",
        'white' => "\033[37m%s\033[0m",
        // background colors
        'bg_black' => "\033[40m%s\033[0m",
        'bg_red' => "\033[41m%s\033[0m",
        'bg_green' => "\033[42m%s\033[0m",
        'bg_yellow' => "\033[43m%s\033[0m",
        'bg_blue' => "\033[44m%s\033[0m",
        'bg_magenta' => "\033[45m%s\033[0m",
        'bg_cyan' => "\033[46m%s\033[0m",
        'bg_white' => "\033[47m%s\033[0m",
    );

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