<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/bootstrap.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Api\Post\PostController;

$app->before(function (Request $req) {
    /*if (0 === strpos($req->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($req->getContent(), true);
        $req->request->replace(is_array($data) ? $data : array());
    }
    return $req;*/
});

$app->get('/', function () use ($app) {
    return $app['twig']->render('inicio.twig');
})->bind('inicio');

$app->after(function (Request $req, Response $res) {
    //$res->headers->set('Content-Type', 'application/json');
    //return $res->json_encode($res);
});

$app->finish(function (Request $req, Response $res) {
    //$res->headers->set('Content-Type', 'application/json');
    //return $res;
});

//Controladoras dos modulos da aplicacao
$app->mount('/post', new PostController($em));

$app->run();
