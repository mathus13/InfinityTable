<?php

namespace Fuel\Migrations;

class Create_user_profiles
{
	public function up()
	{
		\DBUtil::create_table('user_profiles', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'md_name' => array('constraint' => 255, 'type' => 'varchar'),
			'md_value' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_profiles');
	}
}