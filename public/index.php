<?php

// Path definitions
define('APP_ENV', getenv('APPLICATION_ENV'));
define('ROOT_PATH', realpath(__DIR__ . '/../'));
define('APP_PATH', ROOT_PATH . '/app');

require APP_PATH . '/bootstrap.php';
