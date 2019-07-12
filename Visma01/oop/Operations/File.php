<?php

namespace Operations;

class File
{
    public static function writeToFile(string $fileName, array $data): bool
    {
        file_put_contents($fileName, $data);
        return true;
    }

    public static function writeToFileString(string $fileName, string $data): bool
    {
        $current = file_get_contents($fileName);
        $current .= $data;
        file_put_contents($fileName, $current);
        return true;
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
