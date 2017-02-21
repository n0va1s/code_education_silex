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
        $ctrl = $app['controllers_factory'];

        $posts = array(
            array('id' => 1, 'conteudo' => 'Esse e o conteudo do post 1 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 2, 'conteudo' => 'Esse e o conteudo do post 2 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 3, 'conteudo' => 'Esse e o conteudo do post 3 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 4, 'conteudo' => 'Esse e o conteudo do post 4 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 5, 'conteudo' => 'Esse e o conteudo do post 5 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 6, 'conteudo' => 'Esse e o conteudo do post 6 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 7, 'conteudo' => 'Esse e o conteudo do post 7 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 8, 'conteudo' => 'Esse e o conteudo do post 8 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 9, 'conteudo' => 'Esse e o conteudo do post 9 <br /><a href=/post/all>Ver todos</a>'),
            array('id' => 10, 'conteudo' => 'Esse e o conteudo do post 10 <br /><a href=/post/all>Ver todos</a>'));

        //Caso as funcoes sejam usadas por varias rotas
        $a = function () {
            echo "Executado apos a funcao desta rota";
        };

        $b = function () {
            echo "Executado antes da funcao desta rota";
        };

        $ctrl->get('/', function () use ($posts) {
            return new Response("Bem-vindo ao modulo Code_Education_Silex/POST <br />", 200);
        })->before($b)->after($a);

        $ctrl->get('/{id}', function ($id) use ($posts, $app) {
            if (empty($posts[$id-1])) {
                $app->abort("Não encontrei o post {$id} <br />");
            }
            return new Response($posts[$id-1]['conteudo'], 200);
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->value('id', 0) //estabelece um valor default
          ->bind('ProjetoFase1');//para fazar links e URLGenerator

        $ctrl->get('/all', function () use ($posts, $app) {
            $html = '';
            foreach ($posts as $post) {
                $html .= '<ul>';
                $html .= '<li><a href=/post/'.$post['id'].'>Post '.$post['id'].'</a>';
                $html .= '</ul>';
            }
            return $html;
        });

        return $ctrl;
    }
}
