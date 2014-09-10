<?php

/**
 * Base controller class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package NotAWordpress
 * @author  Fike Etki <etki@etki.name>
 */
class Controller extends CController
{
    /**
     * Preserving twig inheritance system.
     *
     * @type bool
     * @since 0.1.0
     */
    public $layout = false;

    /**
     * Special hook to pass charset as an HTTP header.
     *
     * @param CAction $action
     *
     * @return bool
     * @since 0.1.0
     */
    public function beforeAction($action)
    {
        header('Content-Type: text/html; charset=' . Yii::app()->charset);
        return true;
    }
}
