<?php
require_once __DIR__.'/../vendor/autoload.php';
//require_once __DIR__.'/../src/bootstrap.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Api\Post\PostController;

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->before(function (Request $req) {
    /*if (0 === strpos($req->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($req->getContent(), true);
        $req->request->replace(is_array($data) ? $data : array());
    }
    return $req;*/
});

$app->get('/', function () {
    
    return new Response('Bem-vindo ao módulo Silex!<br />
                         Use as seguintes rotas:<br />
                         Projeto fase 1 = /post/{id}<br />
                         Projeto fase 2 = /post/all<br />', 200);
});

$app->after(function (Request $req, Response $res) {
    //$res->headers->set('Content-Type', 'application/json');
    //return $res->json_encode($res);
});

$app->finish(function (Request $req, Response $res) {
    //$res->headers->set('Content-Type', 'application/json');
    //return $res;
});

//Controladoras dos modulos da aplicacao
$app->mount('/post', new PostController());

$app->run();
