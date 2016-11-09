<?php
namespace wikiapp\utils;

// http://localhost/main.php/@@@/@@@/?@@=@@

class HttpRequest extends AbstractHttpRequest {
    function __construct() {
        $this->script_name = $_SERVER['SCRIPT_NAME'];
        $this->path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $this->query = $_SERVER['QUERY_STRING'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->get = '';
        $this->post = '';
    }

    function getRoot() {
        //return substr($this->script_name, 0, strrpos($this->script_name, DIRECTORY_SEPARATOR));
        return dirname($this->script_name);
    }

    function getControler() {
        if(isset($this->path_info)) {
            $path_info = explode(DIRECTORY_SEPARATOR, $this->path_info);
            if(array_key_exists(1, $path_info))
                return $path_info[1];
        }
        return false;
    }

    function getAction() {
        if(isset($this->path_info)) {
            $path_info = explode(DIRECTORY_SEPARATOR, $this->path_info);
            if(array_key_exists(2, $path_info))
                return $path_info[2];
        }
        return false;
    }
}
