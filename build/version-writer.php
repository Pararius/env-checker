#!/usr/bin/env php
<?php

declare(strict_types=1);

$pathToJson = __DIR__ . '/../box.json';
$config = json_decode(file_get_contents($pathToJson), true);

if (!isset($config['replacements'])) {
    $config['replacements'] = [];
}

$newVersion = $argv[1] ?? $_SERVER['APP_VERSION'] ?? false;

if (!$newVersion) {
    throw new RuntimeException('No version has been provided to write; neither as an argument nor as an environment variable (APP_VERSION)');
}

$config['replacements']['version'] = $newVersion;

$newContent = json_encode($config, JSON_PRETTY_PRINT);

file_put_contents($pathToJson, $newContent);

echo sprintf('Replaced version with %s%s', $newVersion, PHP_EOL);
