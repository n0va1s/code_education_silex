<?php
require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Ces\Post\PostController;

$app = new Silex\Application();
$app['debug'] = true;

$app->before(function (Request $req) {
    echo 'Executado antes do request';
});

$app->get('/', function () {
    
    return new Response('Bem-vindo ao módulo Silex!<br />
                         Use as seguintes rotas:<br />
                         Projeto fase 1 = /post/{id}<br />
                         Projeto fase 2 = /post/all<br />', 200);
});

$app->after(function (Request $req, Response $res) {
    echo 'Executado antes do response para o browser';
});

$app->finish(function (Request $req, Response $res) {
    echo 'Executado apos o response para o browser';
});

//Controladoras dos modulos da aplicacao
$app->mount('/post', new PostController());

$app->run();
