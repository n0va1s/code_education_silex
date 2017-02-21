<?php
namespace Api\Post;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
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

        $ctrl->get('/', function () use ($posts) {
            return new Response("Bem-vindo ao modulo POST do curso de Siles da Code Education <br />", 200);
        })->before($b)->after($a);

        $ctrl->get('/{id}', function ($id) use ($app) {
            $posts = $app['post']->listar();
            if (empty($posts[$id-1])) {
                return $app->abort("Não encontrei o post {$id} <br />");
            }
            return new Response($posts[$id-1]['conteudo']."<br /><a href=/post/all/html>Ver todos</a>", 200);
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->value('id', 0) //estabelece um valor default
          ->bind('ProjetoFase1');//para fazar links e URLGenerator

        $ctrl->get('/all/json', function() use ($app) {
            return new Response($app->json($app['post']->listar()), 201);
        });

        $ctrl->get('/all/html', function() use ($app) {
            $posts = $app['post']->listar();
            $html = '';
            foreach ($posts as $post) {
                $html .= '<ul>';
                $html .= '<li><a href=/post/'.$post['id'].'>Post '.$post['id'].'</a>';
                $html .= '</ul>';
            }
            return new Response($html, 201);
        });

        return $ctrl;
    }
}
