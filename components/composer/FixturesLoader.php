<?php

namespace Etki\NotAWordpress\Composer;

/**
 * Loads all available fixtures.
 *
 * @version    0.1.0
 * @since      0.1.0
 * @package    Notawordpress
 * @subpackage Composer
 * @author     Fike Etki <etki@etki.name>
 */
class FixturesLoader extends AbstractComposerScriptHandler
{
    /**
     * Populates database with fixture data.
     *
     * @return void
     * @since 0.1.0
     */
    public static function run()
    {
        static::init();
        $fixtureManager = static::getFixtureManager();
        static::notify('Populating tables with fixtures');
        $fixtureManager->prepare();
    }

    /**
     * Creates CDbFixtureManager instance.
     *
     * @return \CDbFixtureManager
     * @since 0.1.0
     */
    protected static function getFixtureManager()
    {
        \Yii::import('system.test.CDbFixtureManager');
        $fixtureManager = new \CDbFixtureManager;
        $fixtureManager->basePath = static::$appRoot . '/fixtures';
        return $fixtureManager;
    }
}
