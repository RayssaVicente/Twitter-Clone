<?php

namespace MF\Controller;

abstract class Action {

    protected $view;

    public function __construct() {
        $this->view = new \stdClass();
    }

    protected function render($view, $layout = 'layout') {
        $this->view->page = $view;

        // __DIR__ aponta para MF/Controller. 
        // /../../ sobe para a raiz do projeto antes de entrar em App/Views
        $caminho_layout = __DIR__ . "/../../App/Views/" . $layout . ".phtml";

        if(file_exists($caminho_layout)) {
            require_once $caminho_layout;
        } else {
            $this->content();
        }
    }

    protected function content() {
        $classAtual = get_class($this);
        $classAtual = str_replace('App\\Controllers\\', '', $classAtual);
        $classAtual = strtolower(str_replace('Controller', '', $classAtual));

        // Caminho absoluto para a view específica
        $arquivo_view = __DIR__ . "/../../App/Views/" . $classAtual . "/" . $this->view->page . ".phtml";

        if(file_exists($arquivo_view)) {
            require_once $arquivo_view;
        } else {
            // Isso ajudará a depurar nos logs da Vercel se o caminho ainda estiver errado
            echo "Erro: Arquivo de View não encontrado em: " . $arquivo_view;
        }
    }
}