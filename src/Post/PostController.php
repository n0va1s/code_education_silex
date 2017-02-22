<?php
namespace Api\Post;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $ctrl = $app['controllers_factory'];
        
        //Caso as funcoes sejam usadas por varias rotas
        $a = function () {
            echo "Executado apos a funcao desta rota";
        };

        $b = function () {
            echo "Executado antes da funcao desta rota";
        };

        $app['post'] = function() {
            return new PostModel;
        };

        $ctrl->get('/', function () {
            return new Response("Bem-vindo ao modulo POST do curso de Siles da Code Education <br />", 200);
        })->before($b)->after($a)->bind('inicioPost');

        $ctrl->get('/{id}', function ($id) use ($app) {
            $posts = $app['post']->listar();
            if (empty($posts[$id-1])) {
                return $app->abort(500, "Não encontrei o post {$id}");
            }
            return $app['twig']->render('post_detalhe.twig', array('post'=>$posts[$id-1]));
            //return new Response($posts[$id-1]['conteudo']."", 200);
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->value('id', 0) //estabelece um valor default
          ->bind('postPorID');//para fazar links e URLGenerator

        $ctrl->get('/all/json', function() use ($app) {
            return new Response($app->json($app['post']->listar()), 201);
        })->bind('postsJson');

        $ctrl->get('/all/html', function() use ($app) {
            return $app['twig']->render('post_lista.twig', array('posts'=>$app['post']->listar()));
            //return new Response($html, 201);
        })->bind('postsHtml');

        return $ctrl;
    }
}
