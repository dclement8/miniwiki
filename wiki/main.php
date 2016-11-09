<?php
session_start();

//use DylanLibrary as d;
use wikiapp\model as m;
use wikiapp\utils as c;

require_once("conf/autoload.php");
require_once __DIR__ . '/vendor/autoload.php';

$http_request = new wikiapp\utils\HttpRequest();

define("ROOT", __DIR__);
define("ACCESS_LEVEL_NONE",  -100);
define("ACCESS_LEVEL_USER",   100);
define("ACCESS_LEVEL_ADMIN",  500);

$router = new wikiapp\utils\Router();
$auth = new wikiapp\utils\Authentification();

$router->addRoute('/wiki/all/',  '\wikiapp\control\WikiController', 'listAll',  ACCESS_LEVEL_NONE);
$router->addRoute('/wiki/view/', '\wikiapp\control\WikiController', 'viewPage', ACCESS_LEVEL_NONE);
$router->addRoute('/wiki/new/', '\wikiapp\control\WikiController', 'newPage', ACCESS_LEVEL_USER);
$router->addRoute('/wiki/save/', '\wikiapp\control\WikiController', 'savePage', ACCESS_LEVEL_USER);
$router->addRoute('/wiki/edit/', '\wikiapp\control\WikiController', 'editPage', ACCESS_LEVEL_USER);
$router->addRoute('/admin/login/', '\wikiapp\control\WikiAdminController', 'loginUser',  ACCESS_LEVEL_NONE);
$router->addRoute('/admin/check/', '\wikiapp\control\WikiAdminController', 'checkUser',  ACCESS_LEVEL_NONE);
$router->addRoute('/admin/logout/', '\wikiapp\control\WikiAdminController', 'logoutUser',  ACCESS_LEVEL_USER);
$router->addRoute('/admin/perso/', '\wikiapp\control\WikiAdminController', 'userSpace',  ACCESS_LEVEL_USER);
$router->addRoute('/admin/create/', '\wikiapp\control\WikiAdminController', 'createUser',  ACCESS_LEVEL_NONE);
$router->addRoute('/admin/add/', '\wikiapp\control\WikiAdminController', 'addUser',  ACCESS_LEVEL_NONE);
$router->addRoute('default',     '\wikiapp\control\WikiController', 'listAll',  ACCESS_LEVEL_NONE);

wikiapp\utils\Router::dispatch($http_request, $auth);
