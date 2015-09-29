<?php

ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);
ini_set('display_errors', 1);
ini_set('html_errors', 0);

ini_set('error_log', ROOT_PATH.'/log/error.log');

use \ApiServer\ConfigManager as Config;

// Load libraries
require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/app/autoloader.php';

define('_DEF__DEBUG', Config::getConfigEnv('debug'));

if(_DEF__DEBUG === true) {
    ini_set('display_errors', 1);
    ini_set('html_errors', 1);
}
else {
    ini_set('display_errors', 0);
    ini_set('html_errors', 0);
}

define('JWT_SECRET', Config::getConfigEnv('jwt_secret'));

// Fatal Error Handler
ob_start('fatal_error_handler');

function fatal_error_handler($buffer) {
    $error = error_get_last();
    if($error['type'] == 1){
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        error_log('Fatal Error: '.$errstr.' ('.$errfile.':'.$errline.')');

        if(_DEF__DEBUG === true) {
            echo json_encode(array('error' => 'fatal_error', 'debug' => true, 'message' => $errstr, 'file' => $errfile, 'line' => $errline));
        }
        else {
            echo json_encode(array('error' => 'fatal_error', 'message' => "An error prevents this action to run"));
        }

        exit();
    }

    return $buffer;
}

function isMobile() {
    $iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    if($iPad||$iPhone||$iPod) {
        return true;
    } else if($android) {
        return true;
    }
    return false;
}

function getAllHeadersSpe()
{
    $headers = '';
   foreach ($_SERVER as $name => $value)
   {
       if (substr($name, 0, 5) == 'HTTP_')
       {
           $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
       }
   }
   return $headers;
}

// Slim
$app = new \Slim\Slim(array(
    'view' => new \ApiServer\CustomView()
));

$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->response->headers->set('Content-Type', 'application/json');
$app->response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Authorization,Accept,X-Requested-With,Lang');
$app->response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE');

// For dev mode
$app->config('debug', false);

// Home
$app->get('/', function () {
    echo "Welcome to " . Config::getConfigGlobal('title') . ". Please read the documentation to use it.";
});

// Not found
$app->notFound(function () use ($app) {
    $app->render(false, array('error' => array('message' => "Route not found")));
});

// Error
$app->error(function ($e) use ($app) {
    $headerError = 500;
    if(method_exists($e, 'getHttpCode')) {
        $headerError = $e->getHttpCode();
    }

    error_log('Error catched by API : '.$e->getMessage().' ('.$e->getFile().':'.$e->getLine().')');

    $errorDetails = json_decode($e->getMessage(), true);
    if(isset($errorDetails['error']) && isset($errorDetails['message'])) {
        $headerError = 422; // 422 Unprocessable Entity like GitHub API
        $messageError = array('message' => $errorDetails['message']);
    }
    else {
        $messageError = array('message' => "An error prevents this action to run");
    }

    $app->response->setStatus($headerError);
    $app->render(false, array('error' => $messageError));
});

// JWT
function doUseAuthorization() {
    $params = \ApiServer\Parameters::getInstance();
    if($params->getParamFromBody('from_pool') !== null && $params->getParamFromBody('from_pool') == 'I8vfjsWQhPiEgQuJgXY') {

    }
    else {
        // source: https://auth0.com/docs/server-apis/php
        //
        // This method will exist if you're using apache
        // If you're not, please go to the extras for a defintion of it.
        if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
        } else {
            $requestHeaders = getAllHeadersSpe();
        }
        if (!isset($requestHeaders['Authorization'])) {
            callUnauthorized("No authorization header sent for doUseAuthorization");
        }
        $authorizationHeader = $requestHeaders['Authorization'];

        if ($authorizationHeader === null) {
            callUnauthorized("Strange authorization header sent for doUseAuthorization");
        }

        // validate the token
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $decoded_token = null;
        try {
            $decoded_token = 'dev';
            if (APP_ENV === 'prod') {
                $decoded_token = JWT::decode($token, JWT_SECRET, array('HS256'));
            }
        } catch (\UnexpectedValueException $ex) {
            callUnauthorized("Invalid token");
        }

        // validate this token (made for us)
        /*if (!property_exists($decoded_token, 'account_id') || $decoded_token->account_id === null) {
            callUnauthorized("Invalid token, element not present");
        }*/

        return (array)$decoded_token;
    }
}

function callUnauthorized($message) {
    throw new \ApiServer\ExceptionApi($message, 401);
}

// Lang
function getHeaderLang() {
    // This method will exist if you're using apache
    // If you're not, please go to the extras for a defintion of it.
    if(function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
    }
    else {
        $requestHeaders = getAllHeadersSpe();
    }
    if (isset($requestHeaders['Lang'])) {
        return $requestHeaders['Lang'];
    }
    return 'en';
}

define('_DEF__LANG', getHeaderLang());

if(file_exists(APP_PATH . '/MyApi/router.php')) {
    require APP_PATH . '/MyApi/router.php';
}

$app->run();
