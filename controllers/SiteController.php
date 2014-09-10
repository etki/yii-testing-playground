<?php
/**
 * Main controller.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Notawordpress
 * @author  Fike Etki <etki@etki.name>
 */
class SiteController extends Controller
{
    /**
     * Renders index page.
     *
     * @return void
     * @since 0.1.0
     */
    public function actionIndex()
    {
        $this->layout = false;
        $this->render('index');
    }

    /**
     * Displays feedback form.
     *
     * @return void
     * @since 0.1.0
     */
    public function actionFeedback()
    {
        $model = new FeedbackForm;
        $dataReceived = $success = false;
        if ($messageData = Yii::app()->request->getPost('FeedbackForm')) {
            $dataReceived = true;
            $success = $model->send($messageData);
        }
        $templateVars = array(
            'model' => $model,
            'success' => $success,
            'dataReceived' => $dataReceived,
        );
        $this->render('feedback', $templateVars);
    }
}
