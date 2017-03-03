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

        $ctrl->get('/incluir', function () use ($app) {
            return $app['twig']->render('post_cadastro.twig', array('post'=>null));
        })->bind('postCadastro');

        $ctrl->get('/editar/{id}', function ($id) use ($app) {
            $posts = $app['posts'];
            $chave = array_search($id, array_column($posts, 'seq_post'));
            if (!isset($chave)) {
                return $app->abort(500, "Não encontrei o post {$id}");
            }
            return $app['twig']->render('post_cadastro.twig', array('post'=>$posts[$chave]));
        })->assert('id', '\d+') //verifica se o parametro e numerico
          ->bind('postAlteracao');//para fazar links e URLGenerator

        $ctrl->post('/gravar', function (Request $req) use ($app) {
            $data = $req->request->all();

            $post = new PostEntity;
            $post->setDesTitulo($data['desTitulo']);
            $post->setTxtConteudo($data['txtConteudo']);
            $post->setNomAutor($data['nomAutor']);
            $post->setDatPublicacao($data['datPost']);

            $this->em->persist($post);
            $this->em->flush();

            if ($post->getSeqPost()) {
                return $app->redirect($app['url_generator']->generate('postLista'));
            } else {
                return $app->abort(500, 'Erro ao salvar o post');
            }
        })->bind('postGravacao');

        $ctrl->post('/atualizar/{id}', function (Request $req, $id) use ($app) {
            $data = $req->request->all();
            $post = $this->em->find('\Api\Post\PostEntity', $id);
            $post->setSeqPost($id);
            $post->setDesTitulo($data['desTitulo']);
            $post->setTxtConteudo($data['txtConteudo']);
            $post->setNomAutor($data['nomAutor']);
            $post->setDatPublicacao($data['datPost']);
            $this->em->flush();

            return $app->redirect($app['url_generator']->generate('postLista'));
        })->bind('postAtualizacao');

        $ctrl->get('/deletar/{id}', function ($id) use ($app) {
            $post = $this->em->find('\Api\Post\PostEntity', $id);
            $this->em->remove($post);
            $this->em->flush();
            return $app->redirect($app['url_generator']->generate('postLista'));
        })->bind('postDelecao');

        $ctrl->get('/all/json', function () use ($app) {
            return new Response($app->json($app['posts']), 201);
        })->bind('postsJson');

        return $ctrl;
    }
}
