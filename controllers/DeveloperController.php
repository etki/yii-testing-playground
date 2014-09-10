<?php
/**
 * Does all the controller work related to project developers.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Notawordpress
 * @author  Fike Etki <etki@etki.name>
 */
class DeveloperController extends Controller
{
    /**
     * Lists all developers.
     *
     * @return void
     * @since 0.1.0
     */
    public function actionIndex()
    {
        $developers = Developer::model()->findAll();
        $this->render('index', array('developers' => $developers,));
    }
}
