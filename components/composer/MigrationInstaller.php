<?php

namespace Etki\NotAWordpress\Composer;

/**
 * Installs all available migrations.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\NotAWordpress\Composer
 * @author  Fike Etki <etki@etki.name>
 */
class MigrationInstaller extends AbstractComposerScriptHandler
{
    public static function run()
    {
        static::notify('Installing migrations');
        static::init();
        $command = static::getMigrationCommand();
        $exitCode = $command->actionUp(array());
        if ($exitCode === 0 || $exitCode === null) {
            static::notify('Successfully installed all migrations');
        } else {
            static::notify('Couldn\'t install migrations, error occurred');
        }
    }

    /**
     * Creates migration command instance.
     *
     * @return \MigrateCommand
     * @since 0.1.0
     */
    public static function getMigrationCommand()
    {
        \Yii::import('system.cli.commands.MigrateCommand');
        $runner = new \CConsoleCommandRunner;
        $command = new \MigrateCommand('migrate', $runner);
        $command->migrationPath
            = \Yii::getPathOfAlias('application.migrations');
        $command->migrationTable = 'sys_migrations';
        $command->connectionID = 'db';
        $command->interactive = 0;
        return $command;
    }
}
