<?php

$env = require __DIR__ . '/env_settings.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "{$env['db']['driver']}:host={$env['db']['host']};dbname={$env['db']['name']}",
    'username' => $env['db']['user'],
    'password' => $env['db']['password'],
    'charset' => $env['db']['charset'],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
