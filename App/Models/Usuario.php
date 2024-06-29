<?php

namespace App\Models;
use MF\Model\Model;
class Usuario extends Model {
    private $id;
    private $nome;
    private $email;
    private $senha;

    private $imagem;
    // Getters e Setters
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    // salvar
    public function salvar() {
        $query = "insert into usuarios(nome, nickname, email, senha, imagem) values(:nome, :nickname, :email, :senha, :imagem)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('nome', $this->__get('nome'));
        $stmt->bindValue('nickname', $this->__get('nickname'));
        $stmt->bindValue('email', $this->__get('email'));
        $stmt->bindValue('senha', $this->__get('senha')); // método md5.
        $stmt->bindValue('imagem', $this->__get('imagem'));
        $stmt->execute();

        return $this;
    }
    public function atualizar() {
        $query = 'update usuarios set nome = :nome, nickname = :nickname, email = :email, descricao = :descricao, imagem = :imagem where id = :id'; 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('nome', $this->__get('nome'));
        $stmt->bindValue('nickname', $this->__get('nickname'));
        $stmt->bindValue('email', $this->__get('email'));
        $stmt->bindValue('descricao', $this->__get('descricao'));
        $stmt->bindValue('imagem', $this->__get('imagem'));
        $stmt->bindValue('id', $this->__get('id'));
        $stmt->execute();

        return $this;
    }
   
    // validar se um cadastro pode ser feito
    public function validarCadastro(){
        $valido = true;
        if(strlen($this->__get('nome')) < 3){
            $valido = false;
        }
        if(strlen($this->__get('nickname')) == '') {
            $valido = false;
        }
        if (strpos($this->__get('nickname'), '@') === false) {
            $valido = false;
        }
        if(strlen($this->__get('email')) == '') {
            $valido = false;
        }
        if(strlen($this->__get('senha')) < 8) {
            $valido = false;
        }
    return $valido;
    }

    // recuperar se o usuário já foi inserido

    public function getUsuarioporEmailAndNickName() {
        $query = "select nome, nickname, email from usuarios where email = :email and nickname = :nickname";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue("email", $this->__get("email"));
        $stmt->bindValue("nickname", $this->__get("nickname"));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function autenticar() {
    $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue("email", $this->__get("email"));
    $stmt->bindValue("senha", $this->__get("senha"));
    $stmt->execute();

    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
    if($usuario['id'] != '' && $usuario['nome'] != '') {
        $this->__set('id', $usuario['id']);
        $this->__set('nome', $usuario['nome']);
    }
    return $this;

    }

    public function getAll () {
        $query = "select u.id, u.nome, u.nickname, u.email, u.imagem, u.descricao,
        (
            select count(*)
            from usuarios_seguidores as us
            where us.id_usuario = :id and us.id_usuario_seguindo = u.id
        ) as seguindo_sn
        from usuarios as u
        where u.nome like :nome or u.nickname like :nickname and u.id != :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":nome", '%'.$this->__get('nome'). '%');
        $stmt->bindValue(":nickname", '%'.$this->__get('nickname'). '%');
        $stmt->bindValue(":id", $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
    }

    public function seguirUsuario($id_usuario_seguindo) {
        $query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values
        (:id_usuario, :id_usuario_seguindo)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    public function deixarSeguir($id_usuario_seguindo) {
        $query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and 
        id_usuario_seguindo = :id_usuario_seguindo';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;

    }

    public function getInfoUsuario() {
        $query = 'select nome, email, nickname, imagem, descricao from usuarios where id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalTweets() {
        $query = 'select count(*) as total_tweet from tweets where id_usuario = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguindo() {
        $query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguidores() {
        $query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}

?>