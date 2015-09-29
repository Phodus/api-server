<?php

namespace ApiServer;

class ApiServerAutoloader
{

    public static function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array('ApiServer\\ApiServerAutoloader', 'loadClass'));
    }

    public static function loadClass($className)
    {
        if (strpos($className, 'ApiServer\\') === 0) {
            $file = dirname(__DIR__) . '/' . str_replace(
                    array("\0", '\\'),
                    array('', DIRECTORY_SEPARATOR),
                    'app/ApiServer/Dependencies'.substr($className,9)
                ) . '.php';

            if (is_file($file)) {
                require $file;
                return true;
            }
        } elseif (strpos($className, 'MyApi\\') === 0) {
            $file = dirname(__DIR__) . '/' . str_replace(
                    array("\0", '\\'),
                    array('', DIRECTORY_SEPARATOR),
                    'app/'.$className
                ) . '.php';

            if (is_file($file)) {
                require $file;
                return true;
            }
        }
        return null;
    }
}

\ApiServer\ApiServerAutoloader::register();
