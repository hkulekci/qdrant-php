<?php

require_once __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env.test';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            putenv(trim($line));
        }
    }
}
