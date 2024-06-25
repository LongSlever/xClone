<?php
namespace App\Controllers;
use MF\Model\Container;
use MF\Controller\Action;

    class AppController extends Action{

        public function timeline() {
            $this->validaAutenticacao();
                // RECUPERAÇÃO DOS TWEETS
                $tweet = Container::getModel('Tweet');
                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweets = $tweet->getAll();
                print_r($tweets);

                $this->view->tweets = $tweets;
            $this->render('timeline');
        }

        public function tweet() {
            $this->validaAutenticacao(); 
                // INSERÇÃO DOS TWEETS NO BANCO
                $tweet = Container::getModel('Tweet');
                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweet->salvar();
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

            $usuarios = array();

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
            if($pesquisarPor != ' ') {
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuarios = $usuario->getAll();

                print_r($usuarios);
            }

            $this->view->usuarios = $usuarios;

            $this->render('quemSeguir');
        }
       
    }
?>
