<?php
namespace Api\Post;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class PostRepository extends EntityRepository
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getPosts()
    {
        $query = $this->em->createQuery('select p from \Api\Post\PostEntity p');
        $posts = $query->getArrayResult();
        return $posts;
    }

    public function getPostsByAutor($autor)
    {
        $query = $this->em->createQuery('select p from \Api\Post\PostEntity p where p.autor = ? order by p.data desc')
                 ->setParameter(1, $autor);
        $posts = $query->getArrayResult();
        return $posts;
    }
}
