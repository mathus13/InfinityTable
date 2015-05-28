<?php namespace Groups;

class Model_Group_User extends \Model_Crud
{
	protected static $_properties = array(
		
		'id',
		'group_id',
		'user_id',
		'role',
	);

	protected static $_table_name = 'group_users';

}
