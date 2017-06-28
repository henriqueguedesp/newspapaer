<?php

namespace Newspaper\Controllers;

use Newspaper\Models\ModeloUsuario;
use Newspaper\Models\ModeloJornalista;
use Symfony\Component\HttpFoundation\Response;
use Newspaper\Util\Sessao;
use Newspaper\Entity\Jornalista;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ControleUsuario {

    private $response;
    private $twig;
    private $request;
    private $sessao;

    function __construct(Response $response, \Twig_Environment $twig, \Symfony\Component\HttpFoundation\Request $request, Sessao $sessao) {
        $this->response = $response;
        $this->twig = $twig;
        $this->request = $request;
        $this->sessao = $sessao;
    }

    public function inicarSistema() {
        if ($this->sessao->get("usuario")) {
            $this->redireciona("/");
        } else {
            $modelo = new ModeloJornalista();
            $dados = $modelo->verificaAdm();
            if ($dados == null) {
                $ultimo = $modelo->ultimoId();
                if ($ultimo == null) {
                    $ultimo = 1;
                    $adm = new Jornalista();
                    $adm->setEmail($ultimo);
                    $adm->setNome($ultimo);
                    $adm->setSenha($ultimo);
                    $adm->setTipo(1);
                    $modelo->cadastrarJornalista($adm);
                    mkdir("uploads/" . $ultimo, 0777, true);
                } else {
                    $ultimo->idJornalista++;
                    $adm = new \Newspaper\Entity\Jornalista();
                    $adm->setEmail($ultimo->idJornalista);
                    $adm->setNome($ultimo->idJornalista);
                    $adm->setSenha($ultimo->idJornalista);
                    $adm->setTipo(1);
                    $modelo->cadastrarJornalista($adm);
                    mkdir("uploads/" . $ultimo->idJornalista, 0777, true);
                }
                return $this->response->setContent($this->twig->render('TemplateAdministradorAdcionado.html.twig', array('adm' => $adm
                )));
            } else {
                return $this->response->setContent($this->twig->render('TemplateComAdministrador.html.twig'));
            }
        }
    }

    public function paginaLogin() {
        if ($this->sessao->get("usuario")) {
            $this->redireciona("/");
        } else {
            return $this->response->setContent($this->twig->render('TemplateLogin.html.twig'));
        }
    }

    public function removerUsuario() {
        if ($this->sessao->get("usuario")) {
            $this->sessao->remove('usuario');
            $this->sessao->delete('usuario');
            $this->redireciona("/");
        } else {
            $this->redireciona("/login");
        }
    }

    public function validaLogin() {
        $modelo = new ModeloUsuario();
        $usuario = $modelo->validaLogin($this->request->get('email'), $this->request->get('senha'));
        if ($usuario) {
            $this->sessao->add("usuario", $usuario);
            echo 1;
        } else {
            echo 0;
        }
    }

    public function redireciona($destino) {
        $redirect = new RedirectResponse($destino);
        $redirect->send();
    }

}
