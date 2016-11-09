<?php

namespace wikiapp\model;
use wikiapp\utils as c;

class User extends AbstractModel {
	protected $id;
	protected $login;
	protected $pass;
	protected $level;

	function __construct() {
		$this->db = c\ConnectionFactory::makeConnection();
	}

	protected function insert() {
		$req = $this->db->prepare("INSERT INTO user VALUES (NULL, ?, ?, ?)");
		if($req->execute(array($this->login, $this->pass, $this->level))) {
			$this->id = $this->db->lastInsertId();
			return $this->id;
		}
		return -1;
	}

	protected function update() {
		$req = $this->db->prepare("UPDATE user SET login = ?, pass = ? WHERE id = ?");
		return $req->execute(array($this->login, $this->pass, $this->id));
	}

	public function save() {
		if(isset($this->id))
			return $this->update();
		return $this->insert();
	}

	public function delete() {
		if(empty($this->id))
			return 0;
		$req = $this->db->prepare("DELETE FROM user WHERE id = ?");
		if($req->execute(array($this->id)))
			return $req->rowCount();
		return 0;
	}

	public static function findById($id) {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM user WHERE id = ?");
		if(!($req->execute(array($id))))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($row = $req->fetch())
			return $row;
		return false;
	}

	public static function findByLogin($login) {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM user WHERE login = ?");
		if(!($req->execute(array($login))))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($row = $req->fetch())
			return $row;
		return false;
	}

	public static function findAll() {
		$db = c\ConnectionFactory::makeConnection();
		$req = $db->prepare("SELECT * FROM user");
		if(!($req->execute()))
			return false;
		$req->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
		if($users = $req->fetchAll())
			return $users;
		return false;
	}

	public function getPages() {
		// Récupère les objets page de l'user
		if(isset($this->id))
			return Page::findByAuthor($this->id);
		return false;
	}
}
