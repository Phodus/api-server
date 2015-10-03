<?php

use \ApiServer\ConfigManager as Config;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$dbConfig = Config::getConfigEnv('mysql');
$db = new medoo([
    'database_type' => 'mysql',
    'database_name' => $dbConfig['database'],
    'server' => $dbConfig['host'],
    'port' => $dbConfig['port'],
    'username' => $dbConfig['username'],
    'password' => $dbConfig['password'],
    'charset' => 'utf8'
]);

function doGenerateUuid() {
    $uuid4 = Uuid::uuid4();
    return str_replace('-', '', $uuid4->toString());
}
