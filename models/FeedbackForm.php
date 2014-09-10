<?php

/**
 * Simple model-based backend for form.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package models
 * @author  Fike Etki <etki@etki.name>
 */
class FeedbackForm extends CModel
{
    /**
     * Sender.
     *
     * @type string
     * @since 0.1.0
     */
    public $from;
    /**
     * Mail subject.
     *
     * @type string
     * @since 0.1.0
     */
    public $subject;
    /**
     * Message itself.
     *
     * @type string
     * @since 0.1.0
     */
    public $message;

    /**
     * Sends message using provided data.
     *
     * @param string[] $data Message data.
     *
     * @return bool Operation success.
     * @since 0.1.0
     */
    public function send(array $data)
    {
        $this->setAttributes($data);
        if (!$this->validate()) {
            return false;
        }
        return mail(
            Yii::app()->params['adminEmail'],
            $this->subject,
            $this->message,
            'From: ' . $this->from
        );
    }

    /**
     * Model attribute names.
     *
     * @return string[] List of attribute names.
     * @since 0.1.0
     */
    public function attributeNames()
    {
        return array(
            'from',
            'subject',
            'message',
        );
    }

    /**
     * Validation rules.
     *
     * @return array Sets of rules.
     * @since 0.1.0
     */
    public function rules()
    {
        return array(
            array(
                'from',
                'email',
                'allowEmpty' => false,
            ),
            array(
                'subject',
                'length',
                'max' => 100,
                'min' => 3,
                'allowEmpty' => false,
            ),
            array(
                'message',
                'length',
                'min' => 10,
                'allowEmpty' => false,
            ),
        );
    }
}
