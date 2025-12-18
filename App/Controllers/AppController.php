<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function timeline() {

    session_start();

    if ($_SESSION['id'] == '' || $_SESSION['nome'] == '') {
        header('Location: /?login=erro');
        return;
    }

    // Tweets
    $tweet = Container::getModel('Tweet');
    $tweet->__set('id_usuario', $_SESSION['id']);
    $this->view->tweets = $tweet->getAll();

    // Busca de usuários (sidebar)
    $usuario = Container::getModel('Usuario');
    $usuarios = [];

    if (isset($_GET['pesquisarPor']) && $_GET['pesquisarPor'] != '') {
        $usuarios = $usuario->getUsuariosPorNome(
            $_GET['pesquisarPor'],
            $_SESSION['id']
        );
    }

    $this->view->usuarios = $usuarios;

    $this->render('timeline');
}



	public function tweet(){

        $this->validaAutenticacao();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('id_usuario', $_SESSION['id']);

		$tweet->salvar();

		header('Location: /timeline');

	}



public function validaAutenticacao() {

		session_start();

		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
			header('Location: /?login=erro');
		}	

	}

	public function quemSeguir() {

		$this->validaAutenticacao();

		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
		
		$usuarios = array();

		if($pesquisarPor != '') {
			
			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $pesquisarPor);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();

		}

		$this->view->usuarios = $usuarios;

		$this->render('quemSeguir');
	}	

	public function acao() {

		$this->validaAutenticacao();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		if($acao == 'seguir') {
			$usuario->seguirUsuario($id_usuario_seguindo);

		} else if($acao == 'deixar_de_seguir') {
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);

		}

		header('Location: /timeline');
	}

	public function remover() {
    // Garante que o usuário está logado
    $this->validaAutenticacao();

    // Recupera o ID do tweet enviado pelo input hidden "tweet_id"
    $id_tweet = isset($_POST['tweet_id']) ? $_POST['tweet_id'] : '';

    if($id_tweet != '') {
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $id_tweet);
        $tweet->__set('id_usuario', $_SESSION['id']); // Segurança: garante que o usuário só apague o próprio tweet

        $tweet->remover();
    }

    // Redireciona de volta para a timeline após remover
    header('Location: /timeline');
}

// public function perfil() {
//     $this->validaAutenticacao();

//     $tweet = Container::getModel('Tweet');
//     $tweet->__set('id_usuario', $_SESSION['id']);

//     // Alterado de getAll() para getPorUsuario()
//     $this->view->tweets = $tweet->getPorUsuario(); 

//     $usuario = Container::getModel('Usuario');
//     $usuario->__set('id', $_SESSION['id']);
//     $this->view->total_tweets = $usuario->getTotalTweets()['total_tweets'];
//     $this->view->total_seguindo = $usuario->getTotalSeguindo()['total_seguindo'];
//     $this->view->total_seguidores = $usuario->getTotalSeguidores()['total_seguidores'];

//     $this->render('perfil');
// }

public function perfil() {
    $this->validaAutenticacao();

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    // ESTA LINHA É A MAIS IMPORTANTE:
    // Ela busca os dados (nome, bio, etc) e entrega para a View
    $this->view->dados_usuario = $usuario->getInfoUsuario(); 

    // Restante do seu código (tweets, seguidores, etc)
    $tweet = Container::getModel('Tweet');
    $tweet->__set('id_usuario', $_SESSION['id']);
    $this->view->tweets = $tweet->getPorUsuario();

    $this->view->total_tweets = $usuario->getTotalTweets()['total_tweets'];
    $this->view->total_seguindo = $usuario->getTotalSeguindo()['total_seguindo'];
    $this->view->total_seguidores = $usuario->getTotalSeguidores()['total_seguidores'];

    $this->render('perfil');
}

public function salvarPerfil() {
    $this->validaAutenticacao();

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    // --- LÓGICA DE UPLOAD DE IMAGENS ---
    // Pasta onde as imagens serão salvas (crie essa pasta em public/uploads)
    $diretorioUpload = __DIR__ . '/../../public/uploads/';
    
    // Processar Imagem de Perfil
    if(isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem_perfil']['name'], PATHINFO_EXTENSION);
        // Cria um nome único para não sobrescrever
        $novoNomePerfil = md5(time() . 'perfil' . $_SESSION['id']) . '.' . $extensao;
        if(move_uploaded_file($_FILES['imagem_perfil']['tmp_name'], $diretorioUpload . $novoNomePerfil)) {
            $usuario->__set('imagem_perfil', $novoNomePerfil);
        }
    }

    // Processar Imagem de Capa
    if(isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem_capa']['name'], PATHINFO_EXTENSION);
        $novoNomeCapa = md5(time() . 'capa' . $_SESSION['id']) . '.' . $extensao;
        if(move_uploaded_file($_FILES['imagem_capa']['tmp_name'], $diretorioUpload . $novoNomeCapa)) {
            $usuario->__set('imagem_capa', $novoNomeCapa);
        }
    }

    // --- LÓGICA DOS DADOS DE TEXTO ---
    $usuario->__set('nome', $_POST['nome']);
    $usuario->__set('biografia', $_POST['biografia']);
    $usuario->__set('localizacao', $_POST['localizacao']);
    $usuario->__set('site', $_POST['site']);

    // Salva no banco
    $usuario->atualizarPerfil();

    // Atualiza o nome na sessão se ele tiver mudado
    $_SESSION['nome'] = $_POST['nome'];

    header('Location: /perfil');
}


}

?>