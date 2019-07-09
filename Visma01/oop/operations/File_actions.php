<?php

namespace operations;

class File{
    public static function WriteToFile(string $fileName, array $data): bool {
        file_put_contents($fileName, $data);
        return true;
    }

    public static function ReadFromFile(string $fileName): array {
        return file($fileName);
    }
}