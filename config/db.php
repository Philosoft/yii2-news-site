<?php

$defaultDB = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];

$localDBConfig = __DIR__ . "/db-local.php";
$localDB = [];

if (is_readable($localDBConfig)) {
    $localDB = require($localDBConfig);
}

return array_merge(
    $defaultDB,
    $localDB
);