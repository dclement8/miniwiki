<?php

namespace wikiapp\control;
use \wikiapp\model as m;

class WikiController {

    /* Attribut pour stocker l'objet HttpRequest */
    private $request=null;

    /*
     * Constructeur :
     *
     * Reçoit une instance de la classe HttRequest et la stocke dans l'attribut
     *    $request
     *
     */

    public function __construct(\wikiapp\utils\HttpRequest $http_req, \wikiapp\utils\Authentification $auth){
        $this->request = $http_req;
        $this->auth = $auth;
    }

    /*
     * Méthode listAll
     *
     * Réalise la fonctionnalité : "afficher l’ensemble des articles"
     *
     */

    public function listAll(){

        /*
         * Algorithme :
         *
         * - Récupérer une liste de toutes les pages (Page::findAll)
         * - Afficher une liste des titres des article
         *
         */
		 $pages = m\Page::findAll();
		 $view = new \wikiapp\view\WikiView($pages);
         $view->render('all');
    }

    /*
     * Méthode viewPage
     *
     * Réalise la fonctionnalité : "afficher un article"
     *
     */

    public function viewPage(){

        /*
         * Algorithme :
         *
         * - Le titre de la page est dans le paramètre de l'URL ($_GET)
         * - Récupérer la page en question (Page::findByTitle)
         * - Afficher le contenu de l'article
         * - Afficher la date de modification de la page et le nom de l'auteur
         *
         */
		 if(isset($_GET['title'])) {
			 $title = urldecode($_GET['title']);
			 $article = m\Page::findByTitle($title);
			 if($article !== false) {
                 $view = new \wikiapp\view\WikiView($article);
                 $view->render('view');
			 }
		 }
    }

    public function newPage() {
        $view = new \wikiapp\view\WikiView();
        $view->render('new');
    }

    public function savePage() {
        if(isset($_POST['title']) && isset($_POST['content'])) {
            $title = urldecode($_POST['title']);
            $article = m\Page::findByTitle($title);
            if($article === false) {
                $page = new m\Page();
                $page->title = $title;
                $page->text = $_POST['content'];
                $page->date = date('Y-m-d');
                $page->author = $_SESSION['user_id'];
                $article_id = $page->save();
                $article = m\Page::findById($article_id);
            }
            elseif($article->author == $_SESSION['user_id'] || $this->auth->access_level == 500) {
                $article->text = $_POST['content'];
                $article->date = date('Y-m-d');
                $article->save();
            }

            if($article !== false) {
                $view = new \wikiapp\view\WikiView($article);
                $view->render('view');
                return;
            }
        }
        $this->newPage();
    }

    public function editPage() {
        if(isset($_GET['title'])) {
            $title = urldecode($_GET['title']);
            $article = m\Page::findByTitle($title);
            if($article !== false) {
                if($article->author == $_SESSION['user_id'] || $this->auth->access_level == 500) {
                    $view = new \wikiapp\view\WikiView($article);
                    $view->render('edit');
                    return;
                }
            }
        }
        $this->listAll();
    }
}
