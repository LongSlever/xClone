<?php
namespace App\Controllers;
use MF\Model\Container;
use MF\Controller\Action;
use PDOException;

    class AppController extends Action{

        public function timeline() {
            session_start();
            if($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $this->render('timeline');
            } else {
                header('Location: /?login=erro');
            }

            
        }
       
    }
?>