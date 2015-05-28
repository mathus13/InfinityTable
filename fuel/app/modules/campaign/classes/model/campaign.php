<?php namespace Campaign;

class Model_Campaign extends \Model_Crud
{
	protected static $_properties = array(
		
		'id',
		'name',
		'created_by',
		'owner',
		'status',
	);

	protected static $_table_name = 'campaigns';

}
