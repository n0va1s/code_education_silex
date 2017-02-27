<?php

namespace Api\Post;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="PostRepository")
 * @ORM\Table(name="Post")
 */
class PostEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text")
     */
    private $conteudo;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $autor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data;

    public function getData()
    {
        return Date('d/m/Y', $this->data);
    }

    public function setData($data)
    {
        $time = strtotime($data);
        $this->data = new \DateTime($time);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }
}
