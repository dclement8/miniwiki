<?php

namespace wikiapp\view;

class WikiAdminView  extends AbstractView{

    /* Constructeur
    *
    * On appelle le constructeur de la classe parent
    *
    */
    public function __construct($data = ''){
        parent::__construct($data);
    }

    protected function renderLogin(){
		$ret = "<form method='post' action='".$this->script_name."/admin/check/'>\n";
		$ret .= "\t<h1>Connexion</h1>\n";

		$ret .= "\t<section>\n";
		$ret .= "\t\t<p><input type='text' placeholder='Username' name='login'></p>\n";
		$ret .= "\t\t<p><input type='password' placeholder='Pass' name='pass'></p>\n";
		$ret .= "\t\t<p><input type='submit'></p>";
		$ret .= "\t</section>\n\n";

		$ret .= "</form>\n";
		return $ret;
    }

	protected function renderPerso(){
		$ret = "\t<section>\n";
		$ret .= "\t\t<h2>Bienvenue dans votre espace</h2>\n";
		$ret .= "\t\t<p>Vos articles :</p>\n";
		$ret .= '<ul>';
        if($this->data) {
    		foreach($this->data as $page) {
    			$ret .= "\t<li>";
                $ret .= "<a href='".$this->script_name."/wiki/view/?title=".urlencode(htmlentities($page->title))."'>".htmlentities($page->title)."</a>\n";
                $ret .= " <a href='".$this->script_name."/wiki/edit/?title=".urlencode(htmlentities($page->title))."'>[Modifier]</a>\n";
                $ret .= "</li>\n";
    		}
        }
		$ret .= "</ul>";
		$ret .= "\t</section>\n\n";
		return $ret;
    }

    protected function renderCreate(){
		$ret = "<form method='post' action='".$this->script_name."/admin/add/'>\n";
		$ret .= "\t<h1>Inscription</h1>\n";

		$ret .= "\t<section>\n";
		$ret .= "\t\t<p><input type='text' placeholder='Username' name='login'></p>\n";
		$ret .= "\t\t<p><input type='password' placeholder='Pass' name='pass'></p>\n";
        $ret .= "\t\t<p><input type='password' placeholder='Pass' name='pass2'></p>\n";
		$ret .= "\t\t<p><input type='submit'></p>";
		$ret .= "\t</section>\n\n";

		$ret .= "</form>\n";
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
        case 'login':
            $main = $this->renderLogin();
            break;

        case 'create':
            $main = $this->renderCreate();
            break;

        case 'perso':
            $main = $this->renderPerso();
            break;

        default:
            $main = $this->renderLogin();
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
