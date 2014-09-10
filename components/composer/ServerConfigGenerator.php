<?php

namespace Etki\NotAWordpress\Composer;

/**
 * This script handler creates configuration files for apache/nginx servers.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\NotAWordpress\Composer
 * @author  Fike Etki <etki@etki.name>
 */
class ServerConfigGenerator extends AbstractComposerScriptHandler
{
    /**
     * Alias for directory containing configuration files tempaltes.
     *
     * @type string
     * @since 0.1.0
     */
    protected static $templateDirAlias = 'application.config.templates';
    /**
     * Alias for directory configuration files are going to be saved to.
     *
     * @type string
     * @since0.1.0
     */
    protected static $targetDirAlias = 'application.runtime';
    /**
     * Pattern for matching hostnames.
     *
     * @type string
     * @since 0.1.0
     */
    protected static $hostPattern
        = '~^(?:\w(?:[\w-]*\w)?\.)*\w(?:[\w-]*\w)?\.?$~';

    /**
     * Initiates config files generation.
     *
     * @return void
     * @since 0.1.0
     */
    public static function run()
    {
        static::init();
        if ($host = self::promptHost()) {
            self::generateConfigFiles($host, static::$appRoot);
        } else {
            static::notify('Skipping server config files creation');
        }
    }

    /**
     * Prompts user for hostname.
     *
     * @return string Hostname, which may be blank or a valid hostname.
     * @since 0.1.0
     */
    protected static function promptHost()
    {
        while (true) {
            static::notify(
                'Please enter hostname to create server configuration files or ' .
                'just leave blank to skip:'
            );
            $host = trim(readline());
            if (empty($host) || preg_match(static::$hostPattern, $host)) {
                return $host;
            }
            static::notify(
                'Invalid hostname: hostname may consist only of letters, ' .
                'digits or hyphens'
            );
        }
    }

    /**
     * Generates config files.
     *
     * @param string $host    Hostname application should be accessible at.
     * @param string $appRoot Application root.
     *
     * @return void
     * @since 0.1.0
     */
    protected static function generateConfigFiles($host, $appRoot)
    {
        $templateDir = \Yii::getPathOfAlias(static::$templateDirAlias);
        $targetDir = \Yii::getPathOfAlias(static::$targetDirAlias);
        $ds = DIRECTORY_SEPARATOR;
        foreach (array('nginx', 'apache',) as $server) {
            $filename = $server . '.conf';
            $templatePath = $templateDir . $ds . $filename;
            $targetPath = $targetDir . $ds . $filename;
            if (file_exists($targetPath)) {
                $message = sprintf(
                    'Config file `%s` exists, overwriting',
                    $filename
                );
                static::notify($message);
            }
            $template = file_get_contents($templatePath);
            $config = str_replace(
                array('%appRoot%', '%host%',),
                array($appRoot, $host,),
                $template
            );
            file_put_contents($targetPath, $config);
            $message = sprintf(
                'Successfully created configuration for `%s` server. ' .
                'Configuration file is saved at `%s`.',
                $server,
                $targetPath
            );
            static::notify($message);
        }
    }
}
