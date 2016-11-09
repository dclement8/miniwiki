<?php
namespace wikiapp\utils;

/*define("ACCESS_LEVEL_NONE",  -100);
define("ACCESS_LEVEL_USER",   100);
define("ACCESS_LEVEL_ADMIN",  500);*/

abstract class AbstractAuthentification {

    /* l'identifiant de l'utilisateur connectÃ© */
    protected $user_login   = null;

    /* son niveau d'accÃ¨s */
    protected $access_level = ACCESS_LEVEL_NONE;

    /* vrai s'il connectÃ© */
    protected $logged_in    = false;

    public function __get($attr_name) {
        if (property_exists( __CLASS__, $attr_name))
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }

    public function __set($attr_name, $attr_val) {
        if (property_exists( __CLASS__, $attr_name))
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
     *  Algorithme du constructeur des classes qui hÃ©ritent de celle ci.
     *
     *  Si la variable de session 'user_login' existe
     *     - renseigner l'attribut user_login avec sa valeur
     *     - renseigner l'attribut access_level avec la valeur de la variable
     *       de session 'access_level'
     *     - mettre l'attribut logged_in a vrai
     *  sinon
     *     - mettre les valeurs null, ACCESS_LEVEL_NONE et false respectivement
     *       dans les trois attribut.
     *
     */



    /*
     * MÃ©thode pour effectuer l'authentification : rÃ©cupÃ¨re le login et le mot
     *      de passe depuis le formulaire de connexion et vÃ©rifie si le
     *      mot de passe correspond a celui stockÃ© dans la variable $fake_user.
     *
     * @param String : $user, le login de l'utilisateur donnÃ©
     * @param String : $pass, le mots de passe donnÃ©

     *  Algorithme:
     *
     *  Recuprer l'utilisateur depuis la BD (ici fake_users)
     *  VÃ©rifier la concordance des mots de passe $pass avec celui stockÃ© en BD
     *    si oui :
     *        - renseigner l'attribut $user_login avec le paramettre $login
     *        - renseigner l'attribut $access_level avec celui stockÃ© en BD
     *        - renseigner $_SESSION['user_login'] avec l'attribut $login
     *        - renseigner $_SESSION['access_level'] avec l'attribut $access_level
     *        - mettre l'attribut $logged_in Ã  vrai
     *    sinon :
     *        - soulever une exception
     *
     */

    abstract public function login($login, $pass);

     /*
      * MÃ©thode pour effectuer la dÃ©connexion : de-initialiser l'attributs
      *    user_id, et mettre logged_in  a faux. Effacer la variable de session
      *    $_SESSION['user_id'].
      *
      * Algorithme :
      *
      * Detruire $_SESSION['user_login'] et $_SESSION['access_right']
      * Effacer les attributs $user_login, $access_level
      * Mettre l'attribut $logged_in a faux
      *
      */

    abstract public function logout() ;


    /*
     * MÃ©thode pour verifier le niveau d'accÃ¨s de l'utilisateur.
     *
     * @param int : $requested, Niveau demandÃ©
     * @return bool : vrai si niveau >= a la valeur de $requested faux sinon
     *
     */

    abstract public function checkAccessRight($requested);

}
