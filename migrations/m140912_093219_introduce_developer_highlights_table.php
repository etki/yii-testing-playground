<?php

class m140912_093219_introduce_developer_highlights_table extends CDbMigration
{
    /**
     * Name of the managed table.
     * 
     * @type string
     * @since 0.1.0
     */
    public $tableName = 'developer_highlights';
    /**
     * Applies migration.
     *
     * @return true
     * @since 0.1.0
     */
    public function up()
    {
        $this->createTable(
            $this->tableName,
            array(
                'id' => 'pk',
                'developer' => 'integer',
                'highlight' => 'string',
                // Foreign key usage prevented fixtures random order deployment
                //'FOREIGN KEY (developer) REFERENCES developers (id) ' .
                //    'ON UPDATE CASCADE ON DELETE CASCADE',
            )
        );
    }
    
    /**
     * Reverts migration.
     *
     * @return true
     * @since 0.1.0
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
