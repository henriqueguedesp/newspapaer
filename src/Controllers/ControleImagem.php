<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Newspaper\Controllers;

/**
 * Description of ControleImagem
 *
 * @author henrique
 */
use Symfony\Component\HttpFoundation\Response;
use Newspaper\Util\Sessao;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ControleImagem {

    private $response;
    private $twig;
    private $request;
    private $sessao;

    public function imagens() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $pasta = "uploads/";
            $pasta .= $usuario->idJornalista;
            $imagens = glob("$pasta/{*.jpg,*.png,*.gif}", GLOB_BRACE);
            return $this->response->setContent($this->twig->render('TemplateListarImagens.html.twig', array('dados' => $imagens, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function novaImagem() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            return $this->response->setContent($this->twig->render('TemplateNovaImagem.html.twig', array('user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function setarImagem() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $file = $this->request->files->get('arquivo');
            $this->colocarImagem();
        } else {
            $this->redireciona("/login");
        }
    }

    public function colocarImagem() {
        $usuario = $this->sessao->get('usuario');
        $target_dir = "uploads/";
        $target_dir .= $usuario->idJornalista ."/";
        $target_file = $target_dir . basename($_FILES["arquivo"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["arquivo"]["tmp_name"]);
            if ($check !== false) {
                echo "<script> alert('O arquivo é uma imagem - " . $check["mime"] . ".'); "
                . " location.href='/listarImagens';</script>";
                $uploadOk = 1;
            } else {
                echo "<script> alert('O arquivo não é uma imagem!'); "
                . " location.href='/listarImagens';</script>";
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            echo "<script> alert('Desculpe, o arquivo já existe em sua galeria!'); "
            . " location.href='/listarImagens';</script>";
            $uploadOk = 0;
        }
        if ($_FILES["arquivo"]["size"] > 500000) {
            echo "<script> alert('A imagem não pode ser carregada, porque ela é muito grande!'); "
            . " location.href='/listarImagens';</script>";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script> alert('Desculpe, só pode ser feito o uploads de arquivos JPG, JPEG, PNG e GIF !'); "
            . " location.href='/listarImagens';</script>";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "<script> alert('Ops... O arquivo não foi enviado. Tente novamente!'); "
            . " location.href='/';</script>";
        } else {
            if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) {
                echo "<script> alert('A imagem salva com sucesso!'); "
                . " location.href='/listarImagens';</script>";
            } else {
                echo "<script> alert('Vishh... Ocorreu um erro ao enviar o arquivo. Tente novamente!'); "
                . " location.href='/listarImagens';</script>";
            }
        }
    }

    public function redireciona($destino) {
        $redirect = new RedirectResponse($destino);
        $redirect->send();
    }

    function __construct(Response $response, \Twig_Environment $twig, \Symfony\Component\HttpFoundation\Request $request, Sessao $sessao) {
        $this->response = $response;
        $this->twig = $twig;
        $this->request = $request;
        $this->sessao = $sessao;
    }

}
