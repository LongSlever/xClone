<?php
namespace App\Controllers;
use MF\Model\Container;
use MF\Controller\Action;

    class AppController extends Action{

        public function timeline() {
            $this->validaAutenticacao();

            $usuario = Container::getModel("Usuario");
            $usuario->__set("id", $_SESSION['id']);
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets =$usuario->getTotalTweets();
            $this->view->total_seguindo =$usuario->getTotalSeguindo();
            $this->view->total_seguidores =$usuario->getTotalSeguidores();

                // RECUPERAÇÃO DOS TWEETS
                $tweet = Container::getModel('Tweet');
                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweets = $tweet->getAll();
                $this->view->tweets = $tweets;
            $this->render('timeline');
        }

        public function tweet() {
            $this->validaAutenticacao(); 
                // INSERÇÃO DOS TWEETS NO BANCO
                $tweet = Container::getModel('Tweet');
                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);
                if(($_POST['tweet']) == '') {
                    header('Location: /timeline?erro=Erro');
                }else {
                    $tweet->salvar();
                    header('Location: /timeline');
                } 
        }

        public function deletar() {
            $this->validaAutenticacao();

            $tweet = Container::getModel('Tweet');
            $tweet->__set('id', $_GET['id']);
            $tweet->deletar();
            header('Location: /timeline');
        }

        public function validaAutenticacao() {
            session_start();

            if(!isset($_SESSION['id'])|| $_SESSION['id'] == '' || !isset($_SESSION['nome'])|| $_SESSION['nome'] == '') {
                header('Location: /?login=erro');
            }else {
            }
        }

        public function quemSeguir() {
            $this->validaAutenticacao();

            // Intância em nível global no módulo para usamos dentro do IF e para informar as views nas informações
            $usuario = Container::getModel('Usuario');
            $usuarios = array();
            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
            if($pesquisarPor != '') {
                
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);
                $usuarios = $usuario->getAll();
            }
            $usuario->__set("id", $_SESSION['id']);
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets =$usuario->getTotalTweets();
            $this->view->total_seguindo =$usuario->getTotalSeguindo();
            $this->view->total_seguidores =$usuario->getTotalSeguidores();
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
           } else if( $acao == 'deixar_de_seguir') {
            $usuario->deixarSeguir($id_usuario_seguindo);
        }

        header('Location: /quemSeguir');
    }    
}
?>
