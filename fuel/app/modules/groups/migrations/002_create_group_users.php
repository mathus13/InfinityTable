<?php

namespace Fuel\Migrations;

class Create_group_users
{
	public function up()
	{
		\DBUtil::create_table('group_users', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'group_id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'role' => array('constraint' => 30, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('group_users');
	}
}