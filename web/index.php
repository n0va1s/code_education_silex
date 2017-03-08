<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/bootstrap.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Api\Post\PostController;

/*
$app->before(function (Request $req) {
    if (0 === strpos($req->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($req->getContent(), true);
        $req->request->replace(is_array($data) ? $data : array());
    }
    return $req;
});
*/

$app->get('/', function () use ($app) {
    return $app['twig']->render('inicio.twig', array(
        //'username' => $app['security']->getToken()->getUser() //Synfony 2.5
        'username' => $app['security.token_storage']->getToken()->getUser() //Synfony 2.6
    ));
})->bind('inicio');

//Nao ha necessidade de uma controladora para login e logout o security intercepta as requisicoes
$app->get('/login', function (Request $req) use ($app) {
var_dump($app['session']->get('_security.last_username'));    
    return $app['twig']->render('login.twig', array(
                'error'         => $app['security.last_error']($req),
                'last_username' => $app['session']->get('_security.last_username'),));
})->bind('login');

//Cria um usuario administrador
$app->get('/login/{username}', function ($username) use ($app) {
    $repo = $app['user_repository'];
    $repo->createAdminUser($username, 'admin');
    return new Response("Administrador criado - {$username}", 200);
})->bind('admin');

/*
$app->after(function (Request $req, Response $res) {
    $res->headers->set('Content-Type', 'application/json');
    return $res->json_encode($res);
});
*/
/*
$app->finish(function (Request $req, Response $res) {
    $res->headers->set('Content-Type', 'application/json');
    return $res;
});
*/

//Controladoras dos modulos da aplicacao
$app->mount('/post', new PostController($em));

$app->run();
