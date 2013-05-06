<?php

// Doctrine DBAL (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'itaya',
    'user'     => 'root',
    'password' => '',
);

$app['db.dsn'] = "mysql:dbname={$app['db.options']['dbname']};host={$app['db.options']['host']}";
