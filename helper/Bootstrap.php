<?php
spl_autoload_register(function ($class) { // Padrão PSR-4
    // project-specific namespace prefix prefixo especifico do projeto
    $prefix = '';
    // base directory for the namespace prefix
    $base_dir = __DIR__.'core/';
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php


    $file = str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});

function formatarData($data_antiga) {
    $date = DateTime::createFromFormat('Y-m-d', $data_antiga);
    return $date->format('d/m/Y');
}
