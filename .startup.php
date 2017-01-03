<?php

require_once 'vendor/autoload.php';

if (Config::debug) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

$folder_input = Config::basedir . Config::folder_input;
$folder_output = Config::basedir . Config::folder_output;

if (!is_dir($folder_input) || !is_dir($folder_output)) {
    mkdir($folder_input, 0755);
    mkdir($folder_output, 0755);
}
