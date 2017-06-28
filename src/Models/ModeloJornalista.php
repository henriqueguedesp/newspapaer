<?php

namespace Newspaper\Models;

use Newspaper\Entity\Jornalista;
use PDO;
use Newspaper\Util\Conexao;

class ModeloJornalista {

    public function verificaSenha($id, $senha) {
        try {
            $sql = "select * from jornalistas where idJornalista = :id and senha = :senha";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':id', $id);
            $p_sql->bindValue(':senha', $senha);
            $p_sql->execute();
            if ($p_sql->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            
        }
    }

    public function deletarJornalista($id) {
        try {
            $sql = "update jornalistas set status = 0 where idJornalista = :id";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':id', $id);
            return $p_sql->execute();
        } catch (Exception $ex) {
            
        }
    }

    public function editarPerfil(Jornalista $jornalista) {
        try {
            $sql = "update jornalistas set nome = :nome ,senha = :senha, email = :email where idJornalista = :id";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':nome', $jornalista->getNome());
            $p_sql->bindValue(':senha', $jornalista->getSenha());
            $p_sql->bindValue(':email', $jornalista->getEmail());
            $p_sql->bindValue(':id', $jornalista->getId());
            return $p_sql->execute();
        } catch (Exception $ex) {
            
        }
    }

    public function editarJornalista(Jornalista $jornalista) {
        try {
            $sql = "update jornalistas set nome = :nome ,senha = :senha, email = :email, adm = :tipo where idJornalista = :id";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':nome', $jornalista->getNome());
            $p_sql->bindValue(':senha', $jornalista->getSenha());
            $p_sql->bindValue(':email', $jornalista->getEmail());
            $p_sql->bindValue(':tipo', $jornalista->getTipo());
            $p_sql->bindValue(':id', $jornalista->getId());
            return $p_sql->execute();
        } catch (Exception $ex) {
            
        }
    }

    public function jornalista($id) {
        try {
            $sql = "select * from jornalistas where idJornalista = :id and status = 1";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(":id", $id);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function ultimoId() {
        try {
            $sql = "select idJornalista  from jornalistas order by idJornalista desc limit 1";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function cadastrarJornalista(Jornalista $jornalista) {
        try {
            $sql = "insert into jornalistas (nome,senha,email,adm,status) values (:nome,:senha,:email,:tipo,1)";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':nome', $jornalista->getNome());
            $p_sql->bindValue(':senha', $jornalista->getSenha());
            $p_sql->bindValue(':email', $jornalista->getEmail());
            $p_sql->bindValue(':tipo', $jornalista->getTipo());
            $p_sql->execute();
            return Conexao::getInstance()->lastInsertId();
        } catch (Exception $ex) {
            
        }
    }

    public function jornalistas($id) {
        try {
            $sql = "select * from jornalistas where idJornalista != :id and status = 1";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(":id", $id);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function verificaAdm() {
        try {
            $sql = "select * from jornalistas where status = 1";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    function __construct() {
        
    }

}
