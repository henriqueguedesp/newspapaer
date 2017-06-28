<?php

namespace Newspaper\Entity;

class Jornalista {

    private $nome;
    private $email;
    private $id;
    private $senha;
    private $tipo;

    function __construct() {
        
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getId() {
        return $this->id;
    }

    function getSenha() {
        return $this->senha;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

}
