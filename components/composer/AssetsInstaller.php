<?php
namespace Etki\NotAWordpress\Composer;

/**
 * Simple downloader for external assets.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\NotAWordpress\Composer
 * @author  Fike Etki <etki@etki.name>
 */
class AssetsInstaller extends AbstractComposerScriptHandler
{
    /**
     * Assets to be downloaded.
     *
     * @type array
     * @since 0.1.0
     */
    protected static $assets = array(
        array(
            'url' => 'http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js',
            'type' => 'js',
            'name' => 'bootstrap',
        ),
        array(
            'url' => 'http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css',
            'type' => 'css',
            'name' => 'bootstrap',
        ),
        array(
            'url' => 'http://fonts.googleapis.com/css?family=PT+Sans:400,700',
            'type' => 'css',
            'name' => 'google-fonts-pt-sans',
        ),
        array(
            'url' => 'https://code.jquery.com/jquery-2.1.1.min.js',
            'type' => 'js',
            'name' => 'jquery',
        )
    );

    /**
     * Downloads assets.
     *
     * @return void
     * @since 0.1.0
     */
    public static function run()
    {
        static::init();
        $webRoot = \Yii::getPathOfAlias('application.public');
        static::notify('Downloading assets');
        foreach (static::$assets as $asset) {
            $ds = DIRECTORY_SEPARATOR;
            $filename = $asset['name'] . '.' . $asset['type'];
            $path = implode($ds, array($webRoot, $asset['type'], $filename));
            if (file_exists($path)) {
                $message = sprintf('Asset `%s` exists, skipping', $filename);
                static::notify($message);
                continue;
            }
            $template = 'Downloading asset `%s` to `%s`';
            static::notify(sprintf($template, $filename, $path));
            file_put_contents($path, fopen($asset['url'], 'r'));
            $message = sprintf('Asset `%s` successfully downloaded', $filename);
            static::notify($message);
        }
    }
}
