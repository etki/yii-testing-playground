<?php

/**
 *
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Notawordpress
 * @author  Fike Etki <etki@etki.name>
 */
class Developer extends CActiveRecord
{
    /**
     *  Virtual attribute for storing calculated gravatar address.
     *
     * @type string
     * @since 0.1.0
     */
    public $gravatar;
    /**
     * Returns model instance.
     *
     * @param string $class Class name.
     *
     * @return CActiveRecord
     * @since 0.1.0
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }

    /**
     * Returns table name for this particular model.
     *
     * @return string Table name.
     * @since 0.1.0
     */
    public function tableName()
    {
        return 'developers';
    }

    /**
     * Calculates gravatar.
     *
     * @return void
     * @since 0.1.0
     */
    protected function afterFind()
    {
        parent::afterFind();
        $hash = md5(strtolower($this->email));
        $this->gravatar = 'http://gravatar.com/avatar/' . $hash;
    }
}
