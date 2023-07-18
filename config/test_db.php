<?php

$env = require __DIR__ . '/env_settings.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "{$env['test_db']['driver']}:host={$env['test_db']['host']};dbname={$env['test_db']['name']}",
    'username' => $env['test_db']['user'],
    'password' => $env['test_db']['password'],
    'charset' => $env['test_db']['charset'],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
