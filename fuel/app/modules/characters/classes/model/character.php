<?php namespace Characters;

class Model_Character extends \Model_Crud
{
	protected static $_properties = array(
		
		'id',
		'user_id',
		'created',
		'closed',
		'closed_date',
		'note',
		'bio',
		'image',
	);

	protected static $_table_name = 'characters';

}
