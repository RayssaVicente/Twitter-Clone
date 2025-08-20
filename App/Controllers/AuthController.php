<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class AuthController extends Action{

    public function autenticar(){
        $usuario = Container::getModel('Usuario');

        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', md5($_POST['senha']));

    
         $retorno = $usuario->autenticar();

         if($usuario->__get('id') != '' && $usuario->__get('nome')){
           session_start();

           $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /time');
         }else{
            echo 'Erro na autenticação';
            header('Location: /?login=erro');
         }
       
    }

    public function sair(){
            session_start();
            session_destroy();
            header('Location: /');
   }

   public function remover() {
    $query = "DELETE FROM tweets WHERE id = :id AND id_usuario = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $this->__get('id'));
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->execute();

    return $this;
   }



}




?>