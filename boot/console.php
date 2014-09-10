<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE') or define('YII_TRACE', 5);

$appRoot = dirname(__DIR__);
require_once $appRoot . '/vendor/autoload.php';
require_once $appRoot . '/vendor/yiisoft/yii/framework/yii.php';
$config = $appRoot . '/config/console.php';

Yii::createConsoleApplication($config)->run();
