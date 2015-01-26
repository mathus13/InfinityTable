<?php namespace Users;

class Model_User extends \Orm\Model {

	public static function createUser($data) {

	}

	private static function _getInstance() {
		if(!self::$_instance){
			self::$_instance = new self;
		}
	}

	private static $_instance;

	protected static $_properties = array(
		'id',
		'username',
		'password',
		'email',
		'activated',
		'banned',
		'ban_reason',
		'last_ip',
		'last_login',
		'created',
		'modified',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'users';

	public function __construct() {

	}

}
