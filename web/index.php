<?php
require_once __DIR__.'/../vendor/autoload.php';
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$posts = array(
  array('id' => 1, 'conteudo' => 'Esse é o conteudo do post 1'),
  array('id' => 2, 'conteudo' => 'Esse é o conteudo do post 2'),
  array('id' => 3, 'conteudo' => 'Esse é o conteudo do post 3'),
  array('id' => 4, 'conteudo' => 'Esse é o conteudo do post 4'),
  array('id' => 5, 'conteudo' => 'Esse é o conteudo do post 5'),
  array('id' => 6, 'conteudo' => 'Esse é o conteudo do post 6'),
  array('id' => 7, 'conteudo' => 'Esse é o conteudo do post 7'),
  array('id' => 8, 'conteudo' => 'Esse é o conteudo do post 8'),
  array('id' => 9, 'conteudo' => 'Esse é o conteudo do post 9'),
  array('id' => 10, 'conteudo' => 'Esse é o conteudo do post 10'));

$app->get('/', function (Silex\Application $app) {
    
    return 'Bem-vindo ao módulo Silex!<br />
    Use as seguintes rotas:<br />
    Projeto fase 1 = /post/{id}<br />';
});

$app->get('/post/{id}', function (Silex\Application $app, $id) use ($posts) {
    if (empty($posts[$id-1])) {
        $app->abort(500, 'Não encontrei o post {$id}');
    }
    return new Response($posts[$id-1]['conteudo'], 200);
});

$app->run();
