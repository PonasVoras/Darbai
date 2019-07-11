<?php

namespace Config;

use Log\Logger;
use Operations\File;
use RuntimeException;

class Config
{
    private $logToFile = true;
    private $logFile = 'oop/Log/log.Log';
    private $configFile = 'oop/Config/config.json';

    public function __construct()
    {
        $configFileJSON = @File::readFromFileString($this->configFile);
        if ($configFileJSON !== false) {
            $configFile = json_decode($configFileJSON, true);
            if (!$this->applyConfigFileData($configFile, array(
                'logToFile',
                'logFile',
                'configFile'))) {
                $this->makeConfigFile();
            }
        }
    }

    public function applyLoggerConfig(Logger $logger): bool
    {
        $logger->setLogToFile($this->logToFile);
        return true;
    }

    public function getLogFile(): string
    {
        return $this->logFile;
    }

    public function getConfigFile(): string
    {
        return $this->configFile;
    }


    private function makeConfigFile(): void
    {
        $jsonConfig = array(
            'logToFile' => $this->logToFile,
            'logFile' => $this->logFile,
            'configFile' => $this->configFile
        );
        //var_dump($jsonConfig);
        if (!File::writeToFileString($this->configFile, json_encode($jsonConfig))) {
            throw new RuntimeException("Cannot create default Config file !");
        }
    }

    //

    private function applyConfigFileData(array $configFile, array $parameters): bool
    {
        $unsuccess = false;
        foreach ($parameters as $parameter) {
            if (isset($configFile[$parameter])) {
                $this->{$parameter} = $configFile[$parameter];
            } else $unsuccess = true;
        }
        return !$unsuccess;
    }

}