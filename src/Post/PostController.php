<?php
namespace Ces\Post;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $post = $app['controllers_factory'];

        $posts = array(
            array('id' => 1, 'conteudo' => 'Esse e o conteudo do post 1'),
            array('id' => 2, 'conteudo' => 'Esse e o conteudo do post 2'),
            array('id' => 3, 'conteudo' => 'Esse e o conteudo do post 3'),
            array('id' => 4, 'conteudo' => 'Esse e o conteudo do post 4'),
            array('id' => 5, 'conteudo' => 'Esse e o conteudo do post 5'),
            array('id' => 6, 'conteudo' => 'Esse e o conteudo do post 6'),
            array('id' => 7, 'conteudo' => 'Esse e o conteudo do post 7'),
            array('id' => 8, 'conteudo' => 'Esse e o conteudo do post 8'),
            array('id' => 9, 'conteudo' => 'Esse e o conteudo do post 9'),
            array('id' => 10, 'conteudo' => 'Esse e o conteudo do post 10'));

        //Caso as funcoes sejam usadas por varias rotas
        $a = function () {
            echo 'Executado apos a funcao desta rota';
        };

        $b = function () {
            echo 'Executado antes da funcao desta rota';
        };

        $post->get('/', function () use ($posts) {
            return new Response('/post', 200);
        });

        $post->get('/{id}', function ($id) use ($posts) {
            if (empty($posts[$id-1])) {
                $post->abort("Não encontrei o post {$id}");
            }
            return new Response($posts[$id-1]['conteudo'], 200);
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->value('id', 0) //estabelece um valor default
          ->bind('ProjetoFase1');//para fazar links e URLGenerator

        $post->get('/all', function () use ($posts) {
            return $post->json($posts, 200);
        })->before($b)->after($a);

        return $post;
    }
}
