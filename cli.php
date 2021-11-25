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

$inputPath = getFullPath('Inputs/Day' . $day . '.txt');

if (file_exists($inputPath)) {
    $input = file_get_contents($inputPath);
} else {
    die('Missing input file for this day.');
}

$className = 'Questions\Day' . $day;

(new $className((int)$part, $input))->solve();