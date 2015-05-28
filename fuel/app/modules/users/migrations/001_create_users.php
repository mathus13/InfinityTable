<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'username' => array('constraint' => 50, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'email' => array('constraint' => 100, 'type' => 'varchar'),
			'activated' => array('constraint' => 1, 'type' => 'int'),
			'banned' => array('constraint' => 1, 'type' => 'int'),
			'ban_reason' => array('constraint' => 255, 'type' => 'varchar'),
			'last_ip' => array('constraint' => 40, 'type' => 'varchar'),
			'last_login' => array('type' => 'datetime'),
			'created' => array('type' => 'datetime'),
			'modified' => array('type' => 'datetime'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}