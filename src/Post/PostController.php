<?php
namespace Api\Post;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController implements ControllerProviderInterface
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

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

        $app['posts'] = function () {
            $postRep = $this->em->getRepository('\Api\Post\PostEntity');
            return $postRep->getPosts();
        };

        $ctrl->get('/', function () use ($app) {
            return $app['twig']->render('post.twig', array('posts' => $app['posts']));
        })->value('', '/') //estabelece um valor default
        ->bind('postLista');

        $ctrl->get('/{id}', function ($id) use ($app) {
            $posts = $app['posts'];
            if (empty($posts[$id-1])) {
                return $app->abort(500, "Não encontrei o post {$id}");
            }
            return $app['twig']->render('post_detalhe.twig', array('post'=>$posts[$id-1]));
            //return new Response($posts[$id-1]['conteudo']."", 200);
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->bind('postPorID');//para fazar links e URLGenerator

        $ctrl->get('/all/json', function () use ($app) {
            return new Response($app->json($app['posts']), 201);
        })->bind('postsJson');

        $ctrl->get('/cadastrar', function () use ($app) {
            return $app['twig']->render('post_cadastro.twig');
        })->bind('postCadastro');

        $ctrl->post('/gravar', function (Request $req) use ($app) {
            $data = $req->request->all();

            $post = new PostEntity;
            $post->setTitulo($data['desTitulo']);
            $post->setConteudo($data['txtConteudo']);
            $post->setAutor($data['nomAutor']);
            $post->setData($data['datPost']);
            
            $this->em->persist($post);
            $this->em->flush();

            if ($post->getId()) {
                return $app->redirect($app['url_generator']->generate('postLista'));
            } else {
                $app->abort(500, 'Erro ao salvar o post');
            }
        })->bind('postGravacao');

        return $ctrl;
    }
}
