<?php
/**
 * Created by PhpStorm.
 * User: fike
 * Date: 12.09.14
 * Time: 14:07
 */
/**
 *
 *
 * @version 0.1.0
 * @since   
 * @package NotAWordpress
 * @author  Fike Etki <etki@etki.name>
 */
class DeveloperHighlight extends CActiveRecord
{
    public function tableName()
    {
        return 'developer_highlights';
    }
    public function relations()
    {
        return array(
            'developer' => array(self::BELONGS_TO, 'Developer', 'developer',),
        );
    }
}
