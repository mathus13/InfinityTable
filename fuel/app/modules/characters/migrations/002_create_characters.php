<?php

namespace Fuel\Migrations;

class Create_characters
{
	public function up()
	{
		\DBUtil::create_table('characters', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'created' => array('type' => 'datetime'),
			'closed' => array('constraint' => 1, 'type' => 'int'),
			'closed_date' => array('type' => 'datetime'),
			'note' => array('type' => 'text'),
			'bio' => array('type' => 'text'),
			'image' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('characters');
	}
}