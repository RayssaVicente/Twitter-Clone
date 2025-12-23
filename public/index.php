<?php
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    
    // 1. Tenta o caminho exato (Ex: MF/Init/Bootstrap.php)
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        // 2. Fallback: Tenta apenas as pastas em minúsculo, mas mantém o nome do Arquivo original
        // Isso resolve se a pasta for 'mf' mas o arquivo for 'Bootstrap.php'
        $parts = explode('\\', $class);
        $fileName = array_pop($parts);
        $folders = strtolower(implode('/', $parts));
        $file_alt = $base_dir . $folders . '/' . $fileName . '.php';
        
        if (file_exists($file_alt)) {
            require_once $file_alt;
        }
    }
});

$route = new \App\Route;