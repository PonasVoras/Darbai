<?php

namespace Operations;

class File
{
    public static function writeToFile(string $fileName, array $data)
    {
        file_put_contents($fileName, $data);
    }

    public static function writeToFileString(string $fileName, string $data)
    {
        $current = file_get_contents($fileName);
        $current .= $data;
        file_put_contents($fileName, $current);
    }

    public static function readFromFile(string $fileName): array
    {
        return file($fileName);
    }

    public static function readFromFileString(string $fileName): string
    {
        return file_get_contents($fileName);
    }
}
