<?php
namespace Ethereal\Db;

use Ethereal\Db as Db;
use Ethereal\Config as Config;
use Ethereal\Db\Table as Table;
use Ethereal\Db\InvalidTableException;

require_once dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php';

class DbTest extends \PHPUnit_Framework_TestCase
{
	private $table;

	public function setup()
	{
		$this->conn = new Db(new Config);
	}


    /**
     * @expectedException Ethereal\Db\InvalidTableException
     */
	public function testBadTablename()
	{
		$this->table = new Table($this->conn, 'test');
	}

	public function testDbInsert()
	{
		$this->table = new Table($this->conn, 'users');
		$res = $this->table->insert(array(
			'username' => 'testuser'
		));
		$this->assertTrue(is_int($res));
	}

	/**
	 * @depends testDbInsert
	 */
	public function testDbSelect()
	{
		$this->table = new Table($this->conn, 'users');
		$sel = $this->table->select()->where('username = ?')->setParameter(0, 'testuser');
		$res = $this->table->fetchAll($sel);
		$this->assertTrue((count($res) > 0));
	}

	/**
	 * @depends testDbSelect
	 */
	public function testDbDelete()
	{
		$this->table = new Table($this->conn, 'users');
		$this->table->delete(array('username' => 'testuser'));
		$sel = $this->table->select()->where('username = ?')->setParameter(0, 'testuser');
		$res = $this->table->fetchAll($sel);
		$this->assertTrue((count($res) == 0));
	}
}
