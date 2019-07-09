<?php


namespace log;

use DateTime;
use operations\File;

class Logger implements LoggerInterface
{

    private $fileName = "hyphenation.log";
    private $logToScreen = false;
    private $logToFile = true;


    public function setLogToScreen(bool $logToScreen)
    {
        $this->logToScreen = $logToScreen;
    }

    public function setLogToFile(bool $logToFile)
    {
        $this->logToFile = $logToFile;
    }

    // TODO : makeMessage class;

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::EMERGENCY, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::ALERT, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::CRITICAL, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::ERROR, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::WARNING, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::INFO, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = array()): void
    {
        $message = $this->makeMessage(LogLevel::DEBUG, $message, $context);
        $this->writeToFile($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log(string $level, $message, array $context = array()): void
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                $this->emergency($message, $context);
                break;
            case LogLevel::ALERT:
                $this->alert($message, $context);
                break;
            case LogLevel::CRITICAL:
                $this->critical($message, $context);
                break;
            case LogLevel::ERROR:
                $this->error($message, $context);
                break;
            case LogLevel::WARNING:
                $this->warning($message, $context);
                break;
            case LogLevel::INFO:
                $this->info($message, $context);
                break;
            case LogLevel::DEBUG:
                $this->debug($message, $context);
                break;
            default:
                //throw new InvalidArgumentException('Bad Log Level');
                print_r('Bad log level');
                break;
        }
    }

    private function writeToFile(string $message): void
    {
        if ($this->logToFile) {
//            $writeToFile = new File();
//            $writeToFile->w;
            print_r($message);
        }
        $this->logToScreenIfNeeded($message);
    }

    private function logToScreenIfNeeded(string $message): void
    {
        if ($this->logToScreen) {
            print_r($message);
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
            if (!is_array($value) && !is_object($value) || method_exists($value, '__toString')) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }
        return $message;
    }


}