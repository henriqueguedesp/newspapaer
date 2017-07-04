<?php


namespace Newspaper\Models;

use Newspaper\Util\Conexao;
use PDO;

class ModeloCategoria {

    
    public function categorias() {
        try {
            $sql = "select * from categorias order by descricao asc";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }
    
}
