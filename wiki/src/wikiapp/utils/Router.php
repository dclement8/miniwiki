<?php

namespace wikiapp\utils;

class Router extends AbstractRouter {
	public function addRoute($url, $ctrl, $mth, $level) {
		self::$routes[$url] = array($ctrl, $mth, $level);
	}

	public static function dispatch(HttpRequest $http_request, Authentification $auth) {
		if(array_key_exists($http_request->path_info, self::$routes)
		&& $auth->checkAccessRight(self::$routes[$http_request->path_info]['2'])) {
			$ctrl = self::$routes[$http_request->path_info]['0'];
			$c = new $ctrl($http_request, $auth);
			$method = self::$routes[$http_request->path_info]['1'];
			$c->$method();
		}
		else {
			$ctrl = self::$routes['default']['0'];
			$c = new $ctrl($http_request, $auth);
			$method = self::$routes['default']['1'];
			$c->$method();
		}
	}
}
