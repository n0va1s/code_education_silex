<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/bootstrap.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Api\Post\PostController;
use \Api\User\UserProvider;

/*
$app->before(function (Request $req) {
    if (0 === strpos($req->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($req->getContent(), true);
        $req->request->replace(is_array($data) ? $data : array());
    }
    return $req;
});
*/

$app->get('/', function() use ($app) {
    return $app['twig']->render('inicio.twig');
})->bind('inicio');

//Nao ha necessidade de uma controladora para login e logout o security intercepta as requisicoes
$app->get('/login', function(Request $req) use ($app) {
    return $app['twig']->render('login.twig', array(
        'error' => $app['security.last_error']($req),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

//Cria um usuario administrador
$app->get('/login/{username}', function ($username) use ($app, $em) {
    //$user = $app['user_encoder'];
    $user = new \Api\User\UserEntity();
    if($app['security.encoder_factory']->getEncoder($user)){
        $userProvider = new \Api\User\UserProvider($em);
        $userProvider->setPasswordEncoder($app['security.encoder_factory']->getEncoder($user));
        $userProvider->createAdminUser($username, 'admin');
        return new Response("Administrador criado - {$username}", 200);
    } else {
         return $app->abort(500, 'Erro ao cadastrar o administrador');
    }
    
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
