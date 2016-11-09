<?php

namespace wikiapp\view;

class WikiView  extends AbstractView{

    /* Constructeur
    *
    * On appelle le constructeur de la classe parent
    *
    */
    public function __construct($data = ''){
        parent::__construct($data);
    }

    /*
     * Retourne le fragment HTML qui réalise une liste de tous les articles dans
     * l'application sous forme d'une liste de titres.
     *
     * Chaque titre est un lien qui permet d'afficher l'article en question.
     *
     * L'attribut $data contient une liste d'objets Page.
     *
     */
    protected function renderAll(){
		$ret = '<ul>';
		foreach($this->data as $page) {
			$ret .= "\t<li><a href='".$this->script_name."/wiki/view/?title=".urlencode(htmlentities($page->title))."'>".htmlentities($page->title)."</a>";
            if($GLOBALS['auth']->access_level == ACCESS_LEVEL_ADMIN) {
                $ret .= " <a href='".$this->script_name."/wiki/edit/?title=".urlencode(htmlentities($page->title))."'>[Modifier]</a>\n";
            }
            $ret .= "</li>\n";
        }
		$ret .= "</ul>";
		return $ret;
    }

    /*
     * Retourne le fragment HTML qui réalise l'affichage d'un article
     *
     * L'attribut $data contient un objet Page
     * Le text de la page est traduit en HTML par la methode:
     *      \Michelf\Markdown::defaultTransform()
     *
     * L'auteur de la page est récupéré par la méthode gerAuthor() de Page
     *
     */
    protected function renderView(){
		$author = $this->data->getAuthor();
		$ret = "<article>\n";
		$ret .= "\t<header><h1>".htmlentities($this->data->title)."</h1></header>\n";

		$ret .= "\t<section>\n";
		if($author !== false)
		   $ret .= "\t\t<p>Publié le ".htmlentities($this->data->date)." par ".htmlentities($author->login)."</p>\n";
		else
		    $ret .= "\t\t<p>Publié le ".htmlentities($this->data->date)."</p>\n";
		$ret .= "\t</section>\n\n";

		$ret .= "\t<section>";
		$ret .= \Michelf\Markdown::defaultTransform(htmlentities($this->data->text));
		$ret .= "\n\t</section>\n";

		$ret .= "</article>\n";
		return $ret;
    }

    protected function renderNew() {
        $ret = "<form method='post' action='".$this->script_name."/wiki/save/'>";
        $ret .= "<p>Titre :<br /><input type='text' name='title'></p>";
        $ret .= "<p><textarea name='content' cols='50' rows='5'></textarea></p>";
        $ret .= "<p><input type='submit'></p>";
        $ret .= "</form>";
        return $ret;
    }

    protected function renderEdit() {
        $ret = "<form method='post' action='".$this->script_name."/wiki/save/'>";
        $ret .= "<p>Titre :<br />".$this->data->title."</p>";
        $ret .= "<p><textarea name='content' cols='50' rows='5'>".$this->data->text."</textarea></p>";
        $ret .= "<p><input type='hidden' name='title' value='".urlencode($this->data->title)."'><input type='submit'></p>";
        $ret .= "</form>";
        return $ret;
    }

    /*
     * Affiche une page HTML complète.
     *
     * En focntion du sélécteur, le contenu de la page changera.
     *
     */
    public function render($selector){


        switch($selector){
        case 'view':
            $main = $this->renderView();
            break;

        case 'all':
            $main = $this->renderAll();
            break;

        case 'new':
            $main = $this->renderNew();
            break;

        case 'edit':
            $main = $this->renderEdit();
            break;

        default:
            $main = $this->renderAll();
            break;
        }

        $style_file = $this->app_root.'/html/style.css';

        $header = $this->renderHeader();
        $menu   = $this->renderMenu();
        $footer = $this->renderFooter();


/*
 * Utilisation de la syntaxe HEREDOC pour ecrire la chaine de caractère de
 * la page entière. Voir la documentation ici:
 *
 * http://php.net/manual/fr/language.types.string.php#language.types.string.syntax.heredoc
 *
 * Noter bien l'utilisation des variable dans la chaine de caractère
 *
 */
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>MiniWiki</title>
        <link rel="stylesheet" href="${style_file}">
    </head>

    <body>

        <header class="theme-backcolor1"> ${header}  </header>

        <section>

            <aside>

                <nav id="menu" class="theme-backcolor1"> ${menu} </nav>

            </aside>

            <article class="theme-backcolor2">  ${main} </article>

        </section>

        <footer class="theme-backcolor1"> ${footer} </footer>

    </body>
</html>
EOT;

    echo $html;

    }


}
