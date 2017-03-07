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
    private $seq_post;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $des_titulo;

    /**
     * @ORM\Column(type="text")
     */
    private $txt_conteudo;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom_autor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dat_publicacao;

    public function getDatPublicacao()
    {
        return $this->dat_publicacao->format(self::DATE_FORMAT);
    }

    public function setDatPublicacao($dat_publicacao)
    {
        //$time = strtotime($dat_publicacao);
        $this->dat_publicacao = new \DateTime($dat_publicacao);
        return $this;
    }

    public function getSeqPost()
    {
        return $this->seq_post;
    }

    public function setSeqPost($seq_post)
    {
        $this->seq_post = $seq_post;
        return $this;
    }

    public function getDesTitulo()
    {
        return $this->des_titulo;
    }

    public function setDesTitulo($des_titulo)
    {
        $this->des_titulo = $des_titulo;
        return $this;
    }

    public function getTxtConteudo()
    {
        return $this->txt_conteudo;
    }

    public function setTxtConteudo($txt_conteudo)
    {
        $this->txt_conteudo = $txt_conteudo;
        return $this;
    }

    public function getNomAutor()
    {
        return $this->nom_autor;
    }

    public function setNomAutor($nom_autor)
    {
        $this->nom_autor = $nom_autor;
        return $this;
    }
}
