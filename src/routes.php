<?php

namespace Newspaper\Routes;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$rotas = new RouteCollection();

$rotas->add('raiz', new Route('/', array('_controller' => 'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticias'))); //chama todas noticias 

$rotas->add('mostrarNoticias', new Route('/noticias', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticias')));

$rotas->add('cadastrarNoticia', new Route('/cadastrarNoticia', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'cadastrarNoticia')));


$rotas->add('noticia', new Route('/noticia/{_param}', array('_controller' => 'Newspaper\Controllers\ControleNoticia', '_method' => 'noticia')));


$rotas->add('login', new Route('/login', array('_controller' =>
    'Newspaper\Controllers\ControleUsuario',
    '_method' => 'paginaLogin')));

$rotas->add('validaLogin', new Route('/validaLogin', array('_controller' =>
    'Newspaper\Controllers\ControleUsuario',
    '_method' => 'validaLogin')));

$rotas->add('sairUsuario', new Route('/sairUsuario', array('_controller' =>
    'Newspaper\Controllers\ControleUsuario',
    '_method' => 'removerUsuario')));

$rotas->add('cadastrarJornalista', new Route('/cadastrarJornalista', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'cadastrarJornalista')));

$rotas->add('editarJornalista', new Route('/editarJornalista/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'editarJornalista')));

$rotas->add('salvarEdicaoJornalista', new Route('/salvarEdicaoJornalista/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'salvarEdicaoJornalista')));

$rotas->add('salvarEdicaoPerfil', new Route('/salvarEdicaoPerfil', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'salvarEdicaoPerfil')));

$rotas->add('deletarJornalista', new Route('/deletarJornalista/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'deletarJornalista')));

$rotas->add('novaImagem', new Route('/novaImagem', array('_controller' =>
    'Newspaper\Controllers\ControleImagem',
    '_method' => 'novaImagem')));

$rotas->add('setarImagem', new Route('/setarImagem', array('_controller' =>
    'Newspaper\Controllers\ControleImagem',
    '_method' => 'setarImagem')));

$rotas->add('imagens', new Route('/listarImagens', array('_controller' =>
    'Newspaper\Controllers\ControleImagem',
    '_method' => 'imagens')));

$rotas->add('editarPerfil', new Route('/editarPerfil', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'editarPerfil')));

$rotas->add('filtrarCategorias', new Route('/filtrar', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'filtrarNoticias')));

$rotas->add('limparFiltros', new Route('/limparFiltros', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'limparFiltros')));

$rotas->add('verificaPerfil', new Route('/verificaPerfil', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista',
    '_method' => 'verificaPerfil')));

$rotas->add('iniciarSistema', new Route('/iniciarSistema', array('_controller' =>
    'Newspaper\Controllers\ControleUsuario',
    '_method' => 'inicarSistema')));


///       ROTAS DE CATEGORIAS DE NOTICIAS

$rotas->add('noticiaDeCiencia', new Route('/ciencia', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasCiencia')));

$rotas->add('noticiaDeDesastresEAcidentes', new Route('/desastresEAcidentes', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasDesastresEAcidentes')));

$rotas->add('noticiaDeEconomia', new Route('/economia', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasEconomia')));

$rotas->add('noticiaDeEducacao', new Route('/educacao', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasEducacao')));

$rotas->add('noticiaDeEducacao', new Route('/educacao', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasEducacao')));

$rotas->add('noticiasDeEsporte', new Route('/esporte', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasEsporte')));

$rotas->add('noticiasDeNegocio', new Route('/negocio', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasNegocio')));

$rotas->add('noticiasDePolitica', new Route('/politica', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasPolitica')));

$rotas->add('noticiasDeSaude', new Route('/saude', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasSaude')));

$rotas->add('noticiasDeTecnologia', new Route('/tecnologia', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasTecnologia')));

$rotas->add('noticiasDeTransporte', new Route('/transporte', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'noticiasTransporte')));


$rotas->add('editarNoticia', new Route('/editarNoticia/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia', '_method' => 'editarNoticia')));

$rotas->add('salvarEdicao', new Route('/salvarEdicao/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia', '_method' => 'salvarEdicao')));


$rotas->add('deletarNoticia', new Route('/deletarNoticia/{_param}', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia', '_method' => 'deletarNoticia')));



$rotas->add('novoJornalista', new Route('/novoJornalista', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista', '_method' => 'novoJornalista')));

$rotas->add('listarJornalistas', new Route('/listarJornalistas', array('_controller' =>
    'Newspaper\Controllers\ControleJornalista', '_method' => 'listarJornalistas')));


$rotas->add('novaNoticia', new Route('/novaNoticia', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'novaNoticia')));

$rotas->add('listarNoticias', new Route('/listarNoticias', array('_controller' =>
    'Newspaper\Controllers\ControleNoticia',
    '_method' => 'listarNoticias')));


return $rotas;
