<?php

namespace Newspaper\Entity;

class Noticia {

    private $id;
    private $titulo;
    private $resumo;
    private $texto;
    private $dataPublicacao;
    private $idJornalista;
    private $idCapa;
    private $idCategoria;

    function getId() {
        return $this->id;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getResumo() {
        return $this->resumo;
    }

    function getCapa() {
        return $this->capa;
    }

    function getTexto() {
        return $this->texto;
    }

    function getDataPublicacao() {
        return $this->dataPublicacao;
    }

    function getIdJornalista() {
        return $this->idJornalista;
    }

    function getIdCapa() {
        return $this->idCapa;
    }

    function getIdCategoria() {
        return $this->idCategoria;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setResumo($resumo) {
        $this->resumo = $resumo;
    }

    function setCapa($capa) {
        $this->capa = $capa;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    function setDataPublicacao($dataPublicacao) {
        $this->dataPublicacao = $dataPublicacao;
    }

    function setIdJornalista($idJornalista) {
        $this->idJornalista = $idJornalista;
    }

    function setIdCapa($idCapa) {
        $this->idCapa = $idCapa;
    }

    function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    function __construct() {
        
    }

}
