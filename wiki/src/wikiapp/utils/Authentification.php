<?php
namespace wikiapp\utils;
use \wikiapp\model as m;

class Authentification extends AbstractAuthentification {
    function __construct() {
        if(isset($_SESSION['user_login'])) {
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        }
        else {
            $this->user_login = null;
            $this->access_level = ACCESS_LEVEL_NONE;
            $this->logged_in = false;
        }
    }

    public function login($login, $pass) {
        $user = m\User::findByLogin($login);
        if($user !== false) {
            if(password_verify($pass, $user->pass)) {
                $this->user_login = $login;
                $this->access_level = $user->level;
                $_SESSION['user_login'] = $this->user_login;
                $_SESSION['user_id'] = $user->id;
                $_SESSION['access_level'] = $this->access_level;
                $this->logged_in = true;
            }
            else
                throw new \Exception('Mot de passe incorrect !');
        }
        else
            throw new \Exception('Utilisateur inexistant !');
    }

    public function logout() {
        unset($_SESSION['user_login']);
        unset($_SESSION['access_level']);
        $this->user_login = null;
        $this->access_level = ACCESS_LEVEL_NONE;
        $this->logged_in = false;
        session_destroy();
    }

    public function checkAccessRight($requested) {
        if($this->access_level >= $requested)
            return true;
        return false;
    }

    function createUser($login, $pass, $level) {
        $user = m\User::findByLogin($login);
        if($user !== false) {
            $new = new m\User();
            $new->login = $login;
            $new->pass = password_hash($pass, PASSWORD_BCRYPT);
            $new->level = $level;

            $new->save();
        }
        else
            throw new \Exception('Utilisateur déjà existant !');
    }
}
