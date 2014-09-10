<?php

$ds = DIRECTORY_SEPARATOR;
$appRoot = dirname(__DIR__);
$sqliteDb = $appRoot . '/runtime/db.sqlite3';

return array(
    'id' => 'Notawordpress/Yii 1.1.15',
    'name' => 'Notawordpress™',
    'basePath' => $appRoot,
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'sqlite:' . $sqliteDb
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                'class' => 'CFileLogRoute',
                'levels' => '*'
            )
        )
    ),
    'params' => array(
        'adminEmail' => 'fike@localhost',
    ),
);
