<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function time() {
		$this->validaAutenticacao();

        //recuperação do tweets
        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll();

        $this->view->tweets = $tweets;

        //$usuario = Container::getModel('Usuario');
        // $usuario->__set('id', $_SESSION['id']);

        //$this->view->info_usuario = $usuario->getInfoUsuario(); //se eu colocar essa informações do usuario, como seguidores, seguindo, e total de tweets em outra view, teri que mudar essas estancias de local
        //$this->view->total_tweets = $usuario->getTotalTweets();
        //$this->view->total_seguindo = $usuario->getTotalSeguindo();
        //$this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->view->usuarios = [];

        $this->render('time');
	}

    public function tweet(){

        $this->validaAutenticacao();
            
        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweet->salvar();

        header('Location: /time');//mudar depos pra time

        
   
    }

    public function validaAutenticacao(){

        session_start();

        if(!isset( $_SESSION['id']) || $_SESSION['id'] == '' || !isset( $_SESSION['nome']) || $_SESSION['nome'] == ''){
            header('Location: /?location=erro');
        }
            
    }

    public function quemSeguir(){
        $this->validaAutenticacao();
       
        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        $usuarios = array();

    
        if($pesquisarPor != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();

        }

        $this->view->usuarios = $usuarios;

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $this->view->tweets = $tweet->getAll();

        $this->render('time');
    }

    public function acao(){
        $this->validaAutenticacao();

        //acao
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';

        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        //id_usuario

        if($acao == 'seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);
        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('Location: /time');
    }

    public function removerTweet() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $_POST['tweet_id']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweet->remover();

        header('Location: /time');
    }


    
}



?>