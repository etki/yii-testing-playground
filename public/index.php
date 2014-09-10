<?php

$appRoot = dirname(__DIR__);
require_once $appRoot . '/vendor/autoload.php';
require_once $appRoot . '/vendor/yiisoft/yii/framework/yii.php';
$config = $appRoot . '/config/web.php';

Yii::createWebApplication($config)->run();
