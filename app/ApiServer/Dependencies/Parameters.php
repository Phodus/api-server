<?php

namespace ApiServer;

class Parameters {

    private static $_instance = null;
    private $pattern_url_path = array();
    private $params = array();


    private function __construct() {
        $this->params = json_decode(file_get_contents('php://input'), true);
    }

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new Parameters();
        }
        return self::$_instance;
    }

    public function setPatternUrlPath($pattern_url_path) {
        $this->pattern_url_path = $pattern_url_path;
    }

    public function getParamFromBody($key) {
        if(isset($this->params[$key])) {
            return $this->params[$key];
        }
        //throw new \ApiServer\ExceptionApi("Parameter \"".$key."\" not found on data sended (".$_SERVER['REQUEST_URI'].")");
        return null;
    }

    public function getParamFormUrl($key) {
        return $this->getParamsFromUrlPath($key);
        throw new \ApiServer\ExceptionApi("Parameter \"".$key."\" not found on the url");
    }

    private function getParamsFromUrlPath($key) {
        if(isset($_SERVER['REQUEST_URI'])) {
            $pattern_explode = explode('/', $this->pattern_url_path);
            $position = null;
            for ($i = 1; $i < count($pattern_explode); $i++) {
                if(substr($pattern_explode[$i], 0, 1) == ':' && substr($pattern_explode[$i], 1) == $key) {
                    $url_explode = explode('/', $_SERVER['REQUEST_URI']);
                    if(isset($url_explode[$i])) {
                        return $url_explode[$i];
                    }
                }
            }
        }
        throw new \ApiServer\ExceptionApi("REQUEST_URI not found");
    }
}
