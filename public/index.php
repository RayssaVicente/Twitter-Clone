<?php
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    
    // Converte exatamente o Namespace em Caminho (Ex: MF\Init\Bootstrap -> MF/Init/Bootstrap.php)
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

$route = new \App\Route;