<?php

	



	spl_autoload_register(function ($class) {
			// Define a raiz do projeto (um nível acima da pasta public)
			$base_dir = __DIR__ . '/../';
			
			// Converte o namespace em caminho de arquivo (ex: App\Route -> App/Route.php)
			$file = $base_dir . str_replace('\\', '/', $class) . '.php';

			if (file_exists($file)) {
					require_once $file;
			}
	});

	// Continue com o restante do seu código (ex: $route = new \App\Route;)

	$route = new \App\Route;
	

?>