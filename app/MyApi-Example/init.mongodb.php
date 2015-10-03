<?php

$mongodbConfig = self::getConfigEnv('mongodb');
$m = new \MongoClient($mongodbConfig['dsn']);
$mongodb = $m->{$mongodbConfig['database']};
