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
            array('id' => 1, 'titulo'=>'Você sabia que...', 'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 1', 'autor' => 'JP', 'data' => '23/02/2017'),
            array('id' => 2, 'titulo'=>'Era uma vez...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 2', 'autor' => 'n0va1s', 'data' => '01/02/2017'),
            array('id' => 3, 'titulo'=>'Hoje no Planalto...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 3', 'autor' => 'n0va1s', 'data' => '17/02/2017'),
            array('id' => 4, 'titulo'=>'O Presidente do ...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 4', 'autor' => 'JP', 'data' => '23/02/2017'),
            array('id' => 5, 'titulo'=>'A empresa ...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 5', 'autor' => 'JP', 'data' => '22/02/2017'),
            array('id' => 6, 'titulo'=>'A NASA acredita...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 6', 'autor' => 'JP', 'data' => '21/02/2017'),
            array('id' => 7, 'titulo'=>'O novo filme...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 7', 'autor' => 'n0va1s', 'data' => '01/01/2017'),
            array('id' => 8, 'titulo'=>'O cenário econômico',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 8', 'autor' => 'JP', 'data' => '10/01/2017'),
            array('id' => 9, 'titulo'=>'Cai na...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 9', 'autor' => 'JP', 'data' => '10/01/2017'),
            array('id' => 10, 'titulo'=>'Hoje é dia de...',  'conteudo' => 'nonononononoonnonononononoonnonononononoonnonononononoonnonononononoon 10', 'autor' => 'JP', 'data' => '19/01/2017'));
		return $posts;
	}
}
