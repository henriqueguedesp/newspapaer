<?php

namespace Newspaper\Models;

use Newspaper\Util\Conexao;
use PDO;

class ModeloUsuario {

    public function validaLogin($email, $senha) {
        try {
            $sql = "select email,nome,idJornalista,adm from jornalistas where email = :email and senha = :senha and status = 1";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(":email", $email);
            $p_sql->bindValue(":senha", $senha);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_OBJ);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function __construct() {
        
    }

}
