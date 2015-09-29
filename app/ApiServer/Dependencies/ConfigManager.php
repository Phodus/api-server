<?php

namespace ApiServer;

class ConfigManager {

    private static $instance = null;
    private $config_global = array();
    private $config_env = array();

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();

            if(!defined('APP_ENV')) {
                throw new \ApiServer\ExceptionApi("Config Problem: environment variable not defined");
            }

            self::$instance->config_global = parse_ini_file(APP_PATH . '/config/config.ini', true);
            self::$instance->config_env = parse_ini_file(APP_PATH . '/config/config.'.APP_ENV.'.ini', true);
        }
        return self::$instance;
    }

    public static function getConfigGlobal($key) {
        $configObj = self::init();
        if(isset($configObj->config_global[$key])) {
            return $configObj->config_global[$key];
        }
        return null;
    }

    public static function getConfigEnv($key) {
        $configObj = self::init();
        if(isset($configObj->config_env[$key])) {
            return $configObj->config_env[$key];
        }
        return null;
    }

    public static function initMongoDbConn() {
        $config = self::getConfigEnv('mongodb');
        $m = new \MongoClient($config['dsn']);
        return $m->{$config['database']};
    }
}
