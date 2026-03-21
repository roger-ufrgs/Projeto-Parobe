<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), "#")) {
            continue;
        }

        list($name, $value) = explode("=", $line, 2);

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
        putenv("$name=$value");
    }
}

loadEnv(__DIR__ . "/.env");