<?php
/**
 * Does all the controller work related to project developers.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package NotAWordpress
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

    /**
     * Displays detail info about particular developer.
     *
     * @param int|string $id Developer's ID.
     *
     * @throws CHttpException (404) Thrown in case developer with such id doesn't
     * exist.
     *
     * @return void
     * @since 0.1.1
     */
    public function actionDetail($id)
    {
        $developer = Developer::model()->with('highlights')->findByPk($id);
        if (!$developer) {
            throw new CHttpException(404);
        }
        $this->render('detail', array('developer' => $developer,));
    }
}
