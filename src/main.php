<?php

namespace Newspaper;

error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

use Newspaper\Util\Sessao;

$sessao = new Sessao;

$sessao->start();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

include 'routes.php';



$loader = new \Twig_Loader_Filesystem(__DIR__ . '/View');
$twig = new \Twig_Environment($loader);

$requestContext = new RequestContext();
$request = Request::createFromGlobals();
$requestContext->fromRequest($request);
$response = new Response();


// tratar !!!!!
$urlMatcher = new UrlMatcher($rotas, $requestContext);
$atributos = $urlMatcher->match($requestContext->getPathInfo());
//print_r($atributo['_controller']);

$controlador = new $atributos['_controller']($response, $twig,$request,$sessao);

if (isset($atributos['_method'])) {
    $metodo = $atributos['_method'];
    if(isset($atributos['_param']))
        $controlador->$metodo($atributos['_param']);
    else
        $controlador->$metodo();
}
    

$response->send();





