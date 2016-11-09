<?php

namespace wikiapp\model;
use wikiapp\utils as c;

class Page extends AbstractModel {
	protected $id;
	protected $title;
	protected $text;
	protected $date;
	protected $author;

	function __construct() {
		$this->db = c\ConnectionFactory::makeConnection();
	}

	protected function insert() {
		$req = $this->db->prepare("INSERT INTO page VALUES (NULL, ?, ?, ?, ?)");
		if($req->execute(array($this->title, $this->text, $this->date, $this->author))) {
			$this->id = $this->db->lastInsertId();
			return $this->id;
		}
		return -1;
	}

	protected function update() {
		$req = $this->db->prepare("UPDATE page SET title = ?, text = ?, date = ?, author = ? WHERE id = ?");
		return $req->execute(array($this->title, $this->text, $this->date, $this->author, $this->id));
	}

	public function save() {
		if(isset($this->id))
			return $this->update();
		return $this->insert();
	}

	public function delete() {
		if(empty($this->id))
			return 0;
		$req = $this->db->prepare("DELETE FROM page WHERE id = ?");
		if($req->execute(array($this->id)))
			return $req->rowCount();
		return 0;
	}

	public static function findById($id) {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM page WHERE id = ?");
		if(!($req->execute(array($id))))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($row = $req->fetch())
			return $row;
		return false;
	}

	public static function findByTitle($title) {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM page WHERE title = ?");
		if(!($req->execute(array($title))))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($row = $req->fetch())
			return $row;
		return false;
	}

	public static function findByAuthor($id) {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM page WHERE author = ?");
		if(!($req->execute(array($id))))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($pages = $req->fetchAll())
			return $pages;
		return false;
	}

	public static function findAll() {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM page");
		if(!($req->execute()))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($pages = $req->fetchAll())
			return $pages;
		return false;
	}

	public function getAuthor() {
		// Récupère l'objet user de l'auteur
		if(isset($this->id))
			return User::findById($this->id);
		return false;
	}
}
