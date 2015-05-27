<?php

namespace Fuel\Migrations;

class Create_character_data
{
	public function up()
	{
		\DBUtil::create_table('character_data', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'character_id' => array('constraint' => 11, 'type' => 'int'),
			'md_name' => array('constraint' => 255, 'type' => 'varchar'),
			'md_value' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('character_data');
	}
}