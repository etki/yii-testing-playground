<?php
namespace Etki\NotAWordpress\Composer;

/**
 * Base class for all composer script handlers.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\NotAWordpress\Composer
 * @author  Fike Etki <etki@etki.name>
 */
abstract class AbstractComposerScriptHandler
{
    /**
     * Application root directory.
     *
     * @type string
     * @since 0.1.0
     */
    protected static $appRoot;
    /**
     * Safely initializes Yii if it hasn't been done before.
     *
     * @return void
     * @since 0.1.0
     */
    protected static function init()
    {
        static::$appRoot = getcwd(); // we're running composer in app root, right?
        if (!class_exists('\Yii', false)) {
            require static::$appRoot . '/vendor/yiisoft/yii/framework/yii.php';
        }
        if (!\Yii::app()) {
            $config = static::$appRoot . '/config/main.php'; // viva la hardcode
            \Yii::createWebApplication($config);
        }
    }

    /**
     * Sends message to user.
     *
     * @param string $message Message to be shown.
     *
     * @return void
     * @since 0.1.0
     */
    protected static function notify($message)
    {
        echo 'NotAWordpress: ' . $message . PHP_EOL;
    }


    /**
     * Runs whatever script handler is designed to run.
     *
     * @return void
     * @since 0.1.0
     */
    //abstract public static function run();
    // Darn those strict standards!
}
