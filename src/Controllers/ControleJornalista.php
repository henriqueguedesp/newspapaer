<?php

namespace Newspaper\Controllers;

use Newspaper\Entity\Jornalista;
use Symfony\Component\HttpFoundation\Response;
use Newspaper\Util\Sessao;
use Newspaper\Models\ModeloJornalista;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ControleJornalista {

    private $response;
    private $twig;
    private $request;
    private $sessao;

    public function verificaPerfil() {
        $senhaDigitada = $this->request->get('senha');
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $modelo = new ModeloJornalista();
            $dados = $modelo->verificaSenha($usuario->idJornalista, $senhaDigitada);
            if ($dados) {
                $modelo = new ModeloJornalista();
                $usuario = $this->sessao->get('usuario');
                $dados = $modelo->jornalista($usuario->idJornalista);
                return $this->response->setContent($this->twig->render('TemplateEditarPerfil.html.twig', array('dados' => $dados, 'user' => $usuario)));
            } else {
                echo "<script> alert('Senha incorreta!'); "
                . " location.href='/';</script>";
            }
        } else {
            $this->redireciona("/login");
        }
    }

    public function listarJornalistas() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario && ($usuario->adm == 1)) {
            $modelo = new ModeloJornalista();
            $usuario = $this->sessao->get('usuario');
            $dados = $modelo->jornalistas($usuario->idJornalista);
            return $this->response->setContent($this->twig->render('TemplateListarJornalistas.html.twig', array('dados' => $dados, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function novoJornalista() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario && ($usuario->adm == 1)) {
            return $this->response->setContent($this->twig->render('TemplateNovoJornalista.html.twig', array('user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function salvarEdicaoPerfil() {
        $jornalista = new Jornalista;
        $jornalista->setEmail($this->request->get('email'));
        $jornalista->setNome($this->request->get('nome'));
        $jornalista->setSenha($this->request->get('senha'));
        $jornalista->setId($this->sessao->get('usuario')->idJornalista);
        $modelo = new ModeloJornalista();
        $modelo->editarPerfil($jornalista);
        $usuario = $this->sessao->get('usuario');
        $usuario->nome = $jornalista->getNome();
        $usuario->email = $jornalista->getEmail();
        $this->sessao->alter('usuario', $usuario);
        echo "<script> alert('Perfil atualizado com sucesso!'); "
        . " location.href='/';</script>";
    }

    public function deletarJornalista($id) {
        $usuario = $this->sessao->get('usuario');
        if ($usuario && ($usuario->adm == 1)) {
            $modelo = new ModeloJornalista();
            $modelo->deletarJornalista($id);
            echo "<script> alert('Jornalista deletado com sucesso!'); "
            . " location.href='/listarJornalistas';</script>";
        } else {
            $this->redireciona("/login");
        }
    }

    public function salvarEdicaoJornalista($id) {
        $jornalista = new Jornalista;
        $jornalista->setEmail($this->request->get('email'));
        $jornalista->setNome($this->request->get('nome'));
        $jornalista->setSenha($this->request->get('senha'));
        $jornalista->setTipo($this->request->get('tipo'));
        $jornalista->setId($id);
        $modelo = new ModeloJornalista();
        $modelo->editarJornalista($jornalista);
        echo "<script> alert('Jornalista atualizado com sucesso!'); "
        . " location.href='/listarJornalistas';</script>";
    }

    public function cadastrarJornalista() {
        $jornalista = new Jornalista();
        $jornalista->setNome($this->request->get('nome'));
        $jornalista->setSenha($this->request->get('senha'));
        $jornalista->setEmail($this->request->get('email'));
        $jornalista->setTipo($this->request->get('tipo'));
        $modelo = new ModeloJornalista();
        $id = $modelo->cadastrarJornalista($jornalista);
        mkdir("uploads/".$id,0777,true);
        echo "<script> alert('Jornalista cadastrado com sucesso!'); "
        . " location.href='/';</script>";
    }

    public function editarJornalista($id) {
        $usuario = $this->sessao->get('usuario');
        if ($usuario && ($usuario->adm == 1)) {
            $modelo = new ModeloJornalista();
            $dados = $modelo->jornalista($id);
            return $this->response->setContent($this->twig->render('TemplateEditarJornalista.html.twig', array('dados' => $dados, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    function __construct(Response $response, \Twig_Environment $twig, \Symfony\Component\HttpFoundation\Request $request, Sessao $sessao) {
        $this->response = $response;
        $this->twig = $twig;
        $this->request = $request;
        $this->sessao = $sessao;
    }

    public function redireciona($destino) {
        $redirect = new RedirectResponse($destino);
        $redirect->send();
    }

}
