<?php

spl_autoload_register(
    function ($className) {
        $classPath = getFullPath($className . '.php');

        if (file_exists($classPath)) {
            require_once getFullPath($className . '.php');
        } else {
            die('No solution implemented for this day.');
        }
    }
);

function getFullPath(string $relativePath): string
{
    return strtr(__DIR__ . '/' . $relativePath, '\\', '/');
}

[, $day, $part] = $argv;
$day = str_pad($day, 2, '0', STR_PAD_LEFT);

$inputPath = getFullPath('Inputs/Day' . $day . '.txt');

if (file_exists($inputPath)) {
    $input = file($inputPath, FILE_IGNORE_NEW_LINES);
} else {
    die('Missing input file for this day.');
}

$className = 'Questions\Day' . $day;

(new $className((int)$part, $input))->solve();