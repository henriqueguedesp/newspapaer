<?php

namespace Newspaper\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Newspaper\Models\ModeloNoticia;
use Newspaper\Models\ModeloCategoria;
use Newspaper\Entity\Noticia;
use Newspaper\Util\Sessao;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Newspaper\Util\Cookie;

class ControleNoticia {

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

    public function limparFiltros() {
        $cookie = new Cookie();
        $cookie->remover('categorias', null);
        $this->redireciona('/');
    }

    public function filtrarNoticias() {
        $categorias = $_POST;
        $cookie = new Cookie();
        $cookie->addCookie('categorias', implode(",", $categorias));
        $this->redireciona('/');
    }

    public function novaNoticia() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $modelo = new ModeloNoticia();
            $dados = $modelo->categorias();
            return $this->response->setContent($this->twig->render('TemplateNovaNoticia.html.twig', array('dados' => $dados, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function listarNoticias() {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $modelo = new ModeloNoticia();
            if ($usuario->adm) {
                $dados = $modelo->noticias();
            } else {
                $dados = $modelo->noticiasJornalista($usuario->idJornalista);
            }
            $news = $dados;
            foreach ($news as $key => $da) {
                $dados[$key]->data = base64_encode(($news[$key]->data));
            }
            return $this->response->setContent($this->twig->render('TemplateListarNoticias.html.twig', array('dados' => $dados, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function deletarNoticia($id) {
        $modelo = new ModeloNoticia();
        $modelo->deletarNoticia($id);
        echo "<script> alert('Noticia retirada do ar com sucesso!'); "
        . " location.href='/listarNoticias';</script>";
    }

    public function salvarEdicao($id) {
        $news = new Noticia();
        $capa = $this->request->files->get('arquivo');
        $news->setIdCategoria($this->request->get('categoria'));
        $news->setResumo($this->request->get('resumo'));
        $news->setTexto($this->request->get('texto'));
        $news->setTitulo($this->request->get('titulo'));
        $news->setId($id);
        $modelo = new ModeloNoticia();
        $opcao = $this->request->get("opcao");
        if (isset($capa)) {
            $id = $modelo->editarNoticia($news, $capa);
            echo "<script> alert('Noticia atualizada com sucesso!'); "
            . " location.href='/listarNoticias';</script>";
        } else {
            if ($opcao == 'sim') {
                $id = $modelo->editarNoticiaSemCapa($news);
                echo "<script> alert('Noticia atualizada com sucesso!');"
                . " location.href='/listarNoticias';</script>";
            } else {
                $id = $modelo->editarNoticia($news, $capa);
                echo "<script> alert('Noticia atualizada com sucesso!');"
                . " location.href='/listarNoticias';</script>";
            }
           
        }
    }

    public function editarNoticia($id) {
        $usuario = $this->sessao->get('usuario');
        if ($usuario) {
            $modelo = new ModeloNoticia();
            $dados = $modelo->noticia($id);
            $categorias = $modelo->categorias();
            return $this->response->setContent($this->twig->render('TemplateEditarNoticia.html.twig', array('dados' => $dados, 'imagem' => base64_encode(($dados->data)), 'categorias' => $categorias, 'user' => $usuario)));
        } else {
            $this->redireciona("/login");
        }
    }

    public function noticias() {
        $modelo = new ModeloNoticia;
        $modeloCategorias = new ModeloCategoria();
        $dadosCatgeorias= $modeloCategorias->categorias();
        $usuario = $this->sessao->get('usuario');
        $cookie = new Cookie();
        if ($cookie->getCookie('categorias')) {
            if ($usuario) {
                $modelo = new ModeloNoticia();
                $dados = $modelo->noticias();
                $news = $dados;
                foreach ($news as $key => $da) {
                    $dados[$key]->data = base64_encode(($news[$key]->data));
                }
                return $this->response->setContent($this->twig->render('TemplateNoticias.html.twig', array('dados' => $dados, 'user' => $usuario,'categorias' => $dadosCatgeorias)));
            } else {
               $valores = explode(',', $cookie->getCookie('categorias'));
                $modelo = new ModeloNoticia();
                $dados = array();
                foreach ($valores as $key => $valor) {
                    if ($key === 0) {
                        $dados = $modelo->noticiasEspecificas($valor);
                   } else {

                        $dados = array_merge($dados, $modelo->noticiasEspecificas($valor));
                    }
                }
                $news = $dados;
                foreach ($news as $key => $da) {
                    $dados[$key]->data = base64_encode(($news[$key]->data));
                }
                return $this->response->setContent($this->twig->render('TemplateNoticias.html.twig', array('dados' => $dados, 'user' => $usuario, 'filtros' => $valores,'categorias' => $dadosCatgeorias)));
            
               
                }
               
        } else {
            $dados = $modelo->noticias();
            $news = $dados;
            foreach ($news as $key => $da) {
                $dados[$key]->data = base64_encode(($news[$key]->data));
            }
            if ($usuario) {
                return $this->response->setContent($this->twig->render('TemplateNoticias.html.twig', array('dados' => $dados, 'user' => $usuario,'categorias' => $dadosCatgeorias)));
            } else {
                return $this->response->setContent($this->twig->render('TemplateNoticias.html.twig', array('dados' => $dados, 'user' => $usuario, 'filtros' => null,'categorias' => $dadosCatgeorias)));
                         return $this->response->setContent($this->twig->render('TemplateCabecalhoAdm.html.twig', array('user' => $usuario)));

                }
        }
    }

    public function cadastrarNoticia() {
        $news = new Noticia();
        $capa = $this->request->files->get('arquivo');
        $news->setIdCategoria($this->request->get('categoria'));
        $news->setResumo($this->request->get('resumo'));
        $news->setTexto($this->request->get('texto'));
        $news->setTitulo($this->request->get('titulo'));
        $usuario = $this->sessao->get('usuario');
        $news->setIdJornalista($usuario->idJornalista);
        $modelo = new ModeloNoticia();
        $id = $modelo->adicionarNoticiaImg($news, $capa);
        echo "<script> alert('Noticia adiconada com sucesso!'); "
        . " location.href='/';</script>";
    }

    public function noticia($id) {
        $modelo = new ModeloNoticia();
        $dados = $modelo->noticia($id);
        $maisNoticias = $modelo->ultimasNoticia($id);
        $news = $maisNoticias;
        foreach ($news as $key => $da) {
            $maisNoticias[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
            return $this->response->setContent($this->twig->render('TemplateNoticia.html.twig', array('dados' => $dados, 'imagem' => base64_encode(($dados->data)), 'user' => $infoUser, 'maisNoticias' => $maisNoticias)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticia.html.twig', array('dados' => $dados, 'imagem' => base64_encode(($dados->data)), 'maisNoticias' => $maisNoticias)));
        }
    }

    public function redireciona($destino) {
        $redirect = new RedirectResponse($destino);
        $redirect->send();
    }

    public function noticiasTransporte() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("TRANSPORTE");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'TRANSPORTE', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'TRANSPORTE')));
        }
    }

    public function noticiasTecnologia() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("TECNOLOGIA");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'TECNOLOGIA', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'TECNOLOGIA')));
        }
    }

    public function noticiasPolitica() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("POLÍTICA");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'POLÍTICA', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'POLÍTICA')));
        }
    }

    public function noticiasNegocio() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("NEGÓCIO");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'NEGÓCIO', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'NEGÓCIO')));
        }
    }

    public function noticiasEducacao() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("EDUCACAO");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'EDUCAÇÃO', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'EDUCAÇÃO')));
        }
    }

    public function noticiasEconomia() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("ECONOMIA");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'ECONOMIA', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'ECONOMIA')));
        }
    }

    public function noticiasDesastresEAcidentes() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("DESASTRES E ACIDENTES");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'DESASTRES E ACIDENTES', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'DESASTRES E ACIDENTES')));
        }
    }

    public function noticiasCiencia() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("CIÊNCIA");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'CIÊNCIA', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'CIÊNCIA')));
        }
    }

    public function noticiasSaude() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("SAÚDE");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
             return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'SAÚDE', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'SAÚDE')));
        }
    }

    public function noticiasEsporte() {
        $modelo = new ModeloNoticia;
        $dados = $modelo->noticiasEspecificas("ESPORTE");
        $news = $dados;
        foreach ($news as $key => $da) {
            $dados[$key]->data = base64_encode(($news[$key]->data));
        }
        if ($this->sessao->get('usuario')) {
            $infoUser = $this->sessao->get('usuario');
           return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'ESPORTE', 'user' => $infoUser)));
        } else {
            return $this->response->setContent($this->twig->render('TemplateNoticiasEspecificas.html.twig', array('dados' => $dados,'cat' => 'ESPORTE')));
        }
    }

}
