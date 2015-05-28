<?php

namespace Fuel\Migrations;

class Create_groups
{
	public function up()
	{
		\DBUtil::create_table('groups', array(
			'id' => array('constraint' => 100, 'type' => 'int'),
			'name' => array('constraint' => 120, 'type' => 'varchar'),
			'moderator' => array('constraint' => 11, 'type' => 'int'),
			'members' => array('type' => 'text'),
			'description' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('groups');
	}
}