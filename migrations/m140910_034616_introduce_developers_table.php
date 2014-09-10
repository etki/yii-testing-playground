<?php

class m140910_034616_introduce_developers_table extends CDbMigration
{
    /**
     * Created table name.
     *
     * @type string
     * @since 0.1.0
     */
    protected $tableName = 'developers';

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
                'name' => 'string',
                'email' => 'string',
            )
        );
        return true;
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
        return true;
	}
}
