<?php

namespace Fuel\Migrations;

class Create_campaigns
{
	public function up()
	{
		\DBUtil::create_table('campaigns', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'created_by' => array('constraint' => 11, 'type' => 'int'),
			'owner' => array('constraint' => 11, 'type' => 'int'),
			'status' => array('constraint' => 30, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('campaigns');
	}
}