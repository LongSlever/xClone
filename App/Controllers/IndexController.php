<?php
namespace App\Controllers;
use MF\Model\Container;
use App\Models\Product;
use App\Models\Info;
use MF\Controller\Action;
use PDOException;

    class IndexController extends Action{

        public function index() {
        
            $this->view->login = isset($_GET['login']) ? $_GET['login'] :'';
            $this->render('index');
        }

        public function inscreverse() {
            $this->view->usuario= array(
                'nome'=> '',
                'email'=> '',
            );

            $this->view->erroCadastro = false;
            $this->render('inscreverse');
            
        }

        public function registrar() {
            try {
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $_POST['nome']);
                $usuario->__set('nickname', $_POST['nickname']);
                $usuario->__set('email', $_POST['email']);              
                $usuario->__set('senha', md5($_POST['senha']));
                $usuario->__set('imagem', $_POST['imagem']);
                if($usuario->validarCadastro() && count($usuario->getUsuarioporEmailAndNickName()) == 0) {
                    $usuario->salvar();
                    $this->render('cadastro');
                }else {
                    $this->view->usuario= array(
                        'nome'=> $_POST['nome'],
                        'email'=> $_POST['email'],
                    );
                    $this->view->erroCadastro = true;
                    $this->render('inscreverse');
                }
            } catch (PDOException $e) {
                echo " Erro : " . $e;
            }

            
        }       
    }
?>