<?php


function AutoLoad($file): void
{
    $file = __DIR__ . "/" . str_replace('\\', '/', $file) . ".php";

    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('AutoLoad');
