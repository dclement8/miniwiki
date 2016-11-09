<?php

namespace wikiapp\model;

abstract class AbstractModel {


    /*
     * Classe abstraite reprÃ©sentant le model
     *
     */


    /* une instance de PDO */
    protected $db;


    public function __get($attr_name) {
        if (property_exists( $this, $attr_name))
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }

    public function __set($attr_name, $attr_val) {
        if (property_exists( $this, $attr_name))
            $this->$attr_name=$attr_val;
        else{
            $emess = __CLASS__ . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
    }

    public function __toString(){
        $prop = get_object_vars ($this);
        $str="";
        foreach ($prop as $name => $val){
            if( !is_array($val) )
                $str .= "$name : $val <br> ";
            else
                $str .= "$name : ". print_r($val, TRUE)."<br>";
        }
        return $str;
    }


    /*
     * Mise a jour d'un enregistrement
     *
     * Met Ã  jour l'Ã©tat l'objet courant dans la base
     *
     * Algorithme :
     *
     * - PrÃ©parer une requÃªte sql update
     * - lier les attributs aux paramettre de la requette
     *  - executer la requÃªtte
     *
     *   @return boolean
     *
     */

    abstract protected function update();

    /*
     * Insertion d'un enregistrement dans la base
     *
     * InsÃ¨re les attribut de l'objet comme une nouvelle ligne dans la table
     * l'objet ne doit pas possÃ©der un id
     *
     * Algorithme :
     *
     * - prÃ©pare une requÃªte d'insertion
     * - lier les attribut aux paramÃ¨tres de la requÃªte
     * - exÃ©cuter la requÃªte
     * - rÃ©cupÃ©rer l'identifiant de la ligne insÃ©rÃ©e avec la mÃ©thode LastInsertId
     *   de la classe PDO
     * - enregistrer l'identifiant dans l'attribut $id
     *
     * @return l'id de la page de la ligne insÃ©rÃ©e ou -1 en ca d'erreur
     *
     */

    abstract protected function insert();

    /*
     * Sauvegarder un enregistrement dans la base
     *
     * Enregistre les attributs de l'objet dans la table
     *
     * Algorithme :
     *
     * - Si l'objet possede un identifiant :
     *     mise Ã  jour de la ligne correspondante (update)
     * - sinon
     * 	   insertion dans une nouvelle ligne (insert)
     *
     * - retourner un boolÃ©en vrai si l'operation a rÃ©ussie ou faux sinon
     *
     *   @return boolean
     *
     */

    abstract public function save();


    /*
     * Suppression d'un enregistrement de la base
     *
     * Supprime la ligne dans la table corrsepondant Ã  l'objet courant
     * L'objet doit possÃ©der un ID
     *
     * Algorithme :
     *
     * - vÃ©rifier la valeur de l'attribut id et retourner 0 si pas d'id
     * - prÃ©parer une requÃªtte de suppression
     * - lier le paramÃ¨tre id
     * - exÃ©cuter la requÃªte
     * - retourner le nombre de ligne supprimer
     *
     * @return integer
     *
     */

    abstract public function delete();


    /*
     * RÃ©cupÃ©rer un enregistrement
     *
     * Retrouve la ligne de la table correspondant au id passÃ© en
     * paramÃ¨tre, retourne un objet.
     *
     * Algorithme :
     *
     * - prÃ©parer une requÃªte de sÃ©lection
     * - lier l'id recherche au paramÃ¨tre
     * - rÃ©cupÃ©rer le rÃ©sultat
     * - crÃ©er un objet du modÃ¨le et l'initialiser avec le rÃ©sultat.
     * - retourner l'objet ou faux si erreur
     *
     * @static
     * @param  integer $id  to find
     * @return renvoie un objet model ou faux
     */

    abstract static public function findById($id);


    /*
     * RÃ©cupÃ©rer tous les enregistrements dans la table
     *
     * Renvoie toutes les lignes de la table sous la forme d'un tableau d'objets
     *
     * Algorithme :
     *
     * - exÃ©cuter une requÃªte de sÃ©lection de tout les ligne de la table
     * - pour chaque lignes du rÃ©sultat, crÃ©er un objet page, le remplir et le
     *   stocker dans un tableau
     * - retourner le tableau
     *
     * @static
     * @return Array renvoie un tableau d'objet modÃ¨le ou vide
     *
     */

    abstract static public function findAll();


}
