<?php
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    
    // Converte App\Route em App/Route.php
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        // Tenta converter tudo para minúsculo caso a pasta física seja 'mf' em vez de 'MF'
        $file_lower = $base_dir . str_replace('\\', '/', strtolower($class)) . '.php';
        if (file_exists($file_lower)) {
            require_once $file_lower;
        }
    }
});

$route = new \App\Route;