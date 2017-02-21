<?php
namespace Api\Post;

class PostModel
{
	private $id;
	private $conteudo;

    public function setId($id) {
        $this->id = $id;
        return $this;
	}
	public function getId() {
        return $this->id;
	}
    public function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
        return $this;
	}
	public function getConteudo() {
        return $this->conteudo;
	}
	public function listar() {
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
		return $posts;
	}
}
