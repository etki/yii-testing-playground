<?php
namespace Etki\NotAWordpress\Composer;

/**
 * This class is responsible for setting correct file permissions as required by
 * Yii.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\NotAWordpress\Composer
 * @author  Fike Etki <etki@etki.name>
 */
class FilesystemPermissionSetter extends AbstractComposerScriptHandler
{
    /**
     * Sets correct file permissions on folders/files.
     * 
     * @return void
     * @since 0.1.0
     */
    public static function run()
    {
        static::init();
        $runtime = \Yii::getPathOfAlias('application.runtime');
        $assets = \Yii::getPathOfAlias('application.public.assets');
        $console = \Yii::getPathOfAlias('application.boot.console');
        
        static::notify('Setting correct filesystem permissions');
        static::chmod($runtime, 0220);
        static::chmod($assets, 0220);
        static::chmod($console, 0110);
        static::notify('Done!');
    }
    /**
     * Adds permissions specified by mask.
     * 
     * @param string $path Path to chmodded item.
     * @param int    $mask Bitmask with new permissions.
     * 
     * @return bool True on success, false otherwise.
     * @since 0.1.0
     */
    protected static function chmod($path, $mask)
    {
        if (is_array($path)) {
            $success = true;
            foreach ($path as $singlePath) {
                $success = $success && static::chmod($singlePath, $mask);
            }
            return $success;
        } else {
            $perms = fileperms($path);
            if (!chmod($path, $perms | $mask)) {
                static::notify(sprintf('Failed to chmod `%s`', $path));
                return false;
            }
            return true;
        }
    }
}
