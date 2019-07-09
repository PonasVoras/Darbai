<?php

namespace operations;

class File
{
    public static function writeToFile(string $fileName, array $data): bool
    {
        file_put_contents($fileName, $data);
        return true;
    }

    public static function readFromFile(string $fileName): array
    {
        return file($fileName);
    }
}