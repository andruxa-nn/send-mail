<?php
@define('KERNEL_PATH', 'classes');

spl_autoload_register(function($classes) {
    if (file_exists($path = KERNEL_PATH . "/{$classes}/{$classes}.php")) {
        require_once $path;
    }
});