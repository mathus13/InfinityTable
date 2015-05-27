<?php namespace Campaign;

class Model_Campaign_User extends \Model_Crud
{
	protected static $_properties = array(
		
		'id',
		'campaign_id',
		'user_id',
		'role',
	);

	protected static $_table_name = 'campaign_users';

}
