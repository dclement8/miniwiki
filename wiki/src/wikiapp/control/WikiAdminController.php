<?php

namespace wikiapp\control;

class WikiAdminController {
    private $request=null;
    private $auth=null;

    public function __construct(\wikiapp\utils\HttpRequest $http_req, \wikiapp\utils\Authentification $auth){
        $this->request = $http_req ;
        $this->auth = $auth;
    }



    /*
     * Méthode loginUser
     *
     * Affiche le formulaire d'authentification
     *
     */

    public function loginUser() {

        /*
         * Algorithme :
         *
         *  - Créer une instance de la classe WikiAdminView
         *  - execute la vue qui affiche le formulaire de connexion.
         *
         */
		 $view = new \wikiapp\view\WikiAdminView();
		 $view->render('login');
    }

    /*
     * Méthode checkUser
     *
     * Verifie l'identifiant et le mot de passe fournie par l'utilisateur
     *
     */

    public function checkUser(){


        /*
         *  Algorithme :
         *
         * - Récupérer les données du formulaire de connexion
         * - Filtrer les donnée !!!
         * - Créer une instance de la classe Authentification et verifier
         *   l'identifiant et le mots de passe

         *   => (Indication : pour simplifier on peut coder une méthode
         *       User::findByName() qui retourne un objet User pour avoir
         *       les information den BD)

         * - Si les informations sont correctes
         *   - afficher l'espace personel de l'utilisateur
         * - sinon
         *   - afficher le formulaire de connexion.
         *
         */
		if(isset($_POST['login']) && isset($_POST['pass'])) {
			 $auth = new \wikiapp\utils\Authentification();
			 $auth->login($_POST['login'], $_POST['pass']);
			 if($auth->logged_in) {
				 $this->userSpace();
				 return true;
			 }
		 }
		 $this->loginUser();
    }

    /*
     * Méthode logoutUser
     *
     * Réalise la deconnexion d'un utilisateur
     *
     */

    public function logoutUser(){

        /*
         * Algorithme :
         *
         * - Exécute la méthode de decconexion de la classe Authentification
         * - Exécute la fonctionalité par défaut de l'application
         *
         */
		 $auth = new \wikiapp\utils\Authentification();
		 $auth->logout();
		 $view = new \wikiapp\view\WikiAdminView();
		 $view->render('default');
     }


    /*
     * userSpace
     *
     * Réalise la fonctionnalité "afficher l'espace de l'utilisateur"
     *
     */

    public function userSpace(){


        /*
         * Algorithme :
         *
         * - si l'utilisateur est connecté
         *    - récupérer les articles de l'utilisteur
         *    - creer la vue necéssaire et afficher les article
         * - sinon
         *    - afficher le formulaire de connexion.
         */
		if(isset($_SESSION['user_login'])) {
			$user = \wikiapp\model\User::findByLogin($_SESSION['user_login']);
			if($user !== false) {
				 $view = new \wikiapp\view\WikiAdminView();
				 $view->data = $user->getPages();
				 $view->render('perso');
				 return true;
			 }
		 }
		 $this->loginUser();
    }

    public function createUser() {
        $view = new \wikiapp\view\WikiAdminView();
        $view->render('create');
    }

    public function addUser() {
        if(isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
            if($_POST['pass'] == $_POST['pass2']) {
                $user = \wikiapp\model\User::findByLogin($_POST['login']);
                if($user === false) {
                    $new_user = new \wikiapp\model\User();
                    $new_user->login = $_POST['login'];
                    $new_user->pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
                    $new_user->level = 100;
                    $new_user->save();
                    $auth = new \wikiapp\utils\Authentification();
       			    $auth->login($_POST['login'], $_POST['pass']);
       				$this->userSpace();
                    return;
                }
            }
        }
        $this->createUser();
    }

}
