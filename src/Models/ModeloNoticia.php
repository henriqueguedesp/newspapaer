<?php

namespace Newspaper\Models;

use Newspaper\Util\Conexao;
use PDO;
use Newspaper\Entity\Noticia;

class ModeloNoticia {

    public function ultimasNoticia($id) {
        try {
            $sql = "select * from noticias as n,categorias as c,jornalistas as j, capas as cp where  n.idCategoria = c.idCategoria and n.idJornalista = j.idJornalista and  n.idCapa  = cp.idCapa and cp.name != :name and n.status = 1 and n.idNoticia != :id order by dataPublicacao desc limit 3";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':name', "SEM-CAPA");
            $p_sql->bindValue(':id', $id);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function deletarNoticia($id) {
        try {
            $sql = "update noticias set status =  0  WHERE idNoticia = :id";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':id', $id);
            $p_sql->execute();
            return Conexao::getInstance()->lastInsertId();
        } catch (Exception $ex) {
            
        }
    }

    public function editarNoticia(Noticia $news, \Symfony\Component\HttpFoundation\File\UploadedFile $imagem = null) {
        try {
            $conexao = Conexao::getInstance();
            $noticia = "update  noticias  set titulo = :titulo ,resumo = :resumo ,texto = :texto,idCategoria = :idCategoria where idNoticia = :idNews";
            $conexao->beginTransaction();
            $editarNoticia = $conexao->prepare($noticia);
            $editarNoticia->bindValue(':titulo', $news->getTitulo());
            $editarNoticia->bindValue(':resumo', $news->getResumo());
            $editarNoticia->bindValue(':texto', $news->getTexto());
            $editarNoticia->bindValue(':idCategoria', $news->getIdCategoria());
            $editarNoticia->bindValue(':idNews', $news->getId());
            $editarNoticia->execute();
            $idN = $conexao->lastInsertId();

            if ($imagem != null) {
                $sql = "select idCapa from noticias where idNoticia =  :idN";
                $pegarCapa = $conexao->prepare($sql);
                $pegarCapa->bindValue(':idN', $news->getId());
                $pegarCapa->execute();
                $idCapa = $pegarCapa->fetch(PDO::FETCH_OBJ);
                $idC = (int) $idCapa->idCapa;
                $capa = "UPDATE capas  SET name = :name, type = :type, data = :data WHERE idCapa = :id ";
                $editarCapa = $conexao->prepare($capa);
                $editarCapa->bindValue(':name', $imagem->getClientOriginalName());
                $editarCapa->bindValue(':type', $imagem->getMimeType());
                $editarCapa->bindValue(':data', file_get_contents($imagem->getPathname()));
                $editarCapa->bindValue(':id', $idC);
                $editarCapa->execute();
            }

            $conexao->commit();
        } catch (Exception $ex) {
            
        }
    }

    public function editarNoticiaSemCapa(Noticia $news) {
        try {
            $conexao = Conexao::getInstance();
            $noticia = "update  noticias  set titulo = :titulo ,resumo = :resumo ,texto = :texto,idCategoria = :idCategoria where idNoticia = :idNews";
            $conexao->beginTransaction();
            $editarNoticia = $conexao->prepare($noticia);
            $editarNoticia->bindValue(':titulo', $news->getTitulo());
            $editarNoticia->bindValue(':resumo', $news->getResumo());
            $editarNoticia->bindValue(':texto', $news->getTexto());
            $editarNoticia->bindValue(':idCategoria', $news->getIdCategoria());
            $editarNoticia->bindValue(':idNews', $news->getId());
            $editarNoticia->execute();
            $idN = $conexao->lastInsertId();

            $sql = "select idCapa from noticias where idNoticia =  :idN";
            $pegarCapa = $conexao->prepare($sql);
            $pegarCapa->bindValue(':idN', $news->getId());
            $pegarCapa->execute();
            $idCapa = $pegarCapa->fetch(PDO::FETCH_OBJ);
            $idC = (int) $idCapa->idCapa;

            $capa = "UPDATE capas  SET name = :name, type = :type, data = :data WHERE idCapa = :id ";
            $editarCapa = $conexao->prepare($capa);
            $editarCapa->bindValue(':name', "SEM-CAPA");
            $editarCapa->bindValue(':type', null);
            $editarCapa->bindValue(':data', null);
            $editarCapa->bindValue(':id', $idC);
            $editarCapa->execute();

            $conexao->commit();
        } catch (Exception $ex) {
            
        }
    }

    public function noticiasEspecificas($cat) {
        try {
            $sql = "select * from noticias as n,categorias as c,jornalistas as j, capas as cp where  n.idCategoria = c.idCategoria and n.idJornalista = j.idJornalista and  n.idCapa  = cp.idCapa and c.descricao = :idCat and n.status = 1 order by n.dataPublicacao desc";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':idCat', $cat);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function noticiasJornalista($id) {
        try {
            $sql = "select * from noticias as n,categorias as c,jornalistas as j, capas as cp where  n.idCategoria = c.idCategoria and n.idJornalista = :idJorna and  n.idCapa  = cp.idCapa and j.idJornalista = :idJorna  and n.status = 1 order by n.dataPublicacao desc";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue(':idJorna', $id);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function noticias() {
        try {
            $sql = "select * from noticias as n,categorias as c,jornalistas as j, capas as cp where  n.idCategoria = c.idCategoria and n.idJornalista = j.idJornalista and  n.idCapa  = cp.idCapa and n.status = 1 order by n.dataPublicacao desc";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function noticia($id) {
        try {
            $sql = "select * from noticias as n,categorias as c,jornalistas as j, capas as cp where  n.idCategoria = c.idCategoria and n.idJornalista = j.idJornalista and  n.idCapa  = cp.idCapa and n.status = 1 and n.idNoticia = :idN";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->bindValue('idN', $id);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function categorias() {
        try {
            $sql = "select * from categorias order by descricao asc";
            $p_sql = Conexao::getInstance()->prepare($sql);
            $p_sql->execute();
            return $p_sql->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $ex) {
            
        }
    }

    public function adicionarNoticiaImg(Noticia $news, \Symfony\Component\HttpFoundation\File\UploadedFile $imagem = null) {
        try {
            $conexao = Conexao::getInstance();
            $adicionarCapa = "insert into capas (name, type, data) values (:nome, :type, :data)";
            $conexao->beginTransaction();
            $capa = $conexao->prepare($adicionarCapa);
            if ($imagem == null) {
                $capa->bindValue(':nome', "SEM-CAPA");
                $capa->bindValue(':type', null);
                $capa->bindValue(':data', null);
            } else {
                $capa->bindValue(':nome', $imagem->getClientOriginalName());
                $capa->bindValue(':type', $imagem->getMimeType());
                $capa->bindValue(':data', file_get_contents($imagem->getPathname()));
            }
            $capa->execute();
            $idCapa = $conexao->lastInsertId();
            $adiconarNoticia = "insert into noticias (titulo,resumo,dataPublicacao,texto,idCategoria,idCapa, idJornalista,status) values (:titulo,:resumo,now(),:texto,:idCategoria, :idCapa, :idJornalista,1)";
            $noticia = $conexao->prepare($adiconarNoticia);
            $noticia->bindValue(':titulo', $news->getTitulo());
            $noticia->bindValue(':resumo', $news->getResumo());
            $noticia->bindValue(':texto', $news->getTexto());
            $noticia->bindValue(':idCategoria', $news->getIdCategoria());
            $noticia->bindValue(':idCapa', $idCapa);
            $noticia->bindValue(':idJornalista', $news->getIdJornalista());
            $noticia->execute();
            $conexao->commit();
        } catch (Exception $ex) {
            
        }
    }

    public function __construct() {
        
    }

}
