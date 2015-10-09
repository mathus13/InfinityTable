<?php
/**
 * \Ethereal\Db\Table
 */
namespace Ethereal\Db;

use Ethereal\Db as Db;
use Ethereal\Db\InvalidTableException;
use Ethereal\Db\Row as Row;

/**
 * \Ethereal\Db\Table
 * Generic table class modeled with Doctrine DBAL
 * @author Shawn Barratt
 * 
 */
class Table
{
	private $db;
	protected $table;
	protected $rowClass = 'Ethereal\Db\Row';

	public function __construct(Db $db, $table)
	{
		$this->db = $db;
		$rows = $this->db->executeQuery('SHOW TABLES LIKE ?', array($table));
		if (!$rows->fetch()) {
			throw new InvalidTableException("Table {$table} does not exist in {$this->db->getDatabase()} on {$this->db->getHost()}");
		}
		$this->table = $table;
	}

	/**
	 * get DBAL query builder
	 * @return Doctrine\DBAL\QueryBuilder
	 * @see http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html
	 */
	private function qb()
	{
		return $this->db->createQueryBuilder();
	}

	public function getTable()
	{
		return $this->table;
	}

	public function getConnection()
	{
		return $this->db;
	}

	public function select($cols = '*')
	{
		return $this->qb()->select($cols)->from($this->table);
	}

	public function insert(array $data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update(array $data, $where = array())
	{
		$update =  $this->qb()->update($this->table);
		foreach ($data as $k => $v) {
			$update->set($k, $v);
		}
		$where_values = array_values($where);
		foreach ($where as $stmt => $v) {
			$update->where($statement);
		}
		return $this->db->executeUpdate($update, $where_values);
	}

	public function fetchAll($sql)
	{
		$rows = array();
		if (is_string($sql)) {
			$res = $this->db->fetchAll($sql) ? $this->db->fetchAll($sql) : array();
			foreach ($res as $data) {
				$rows[] = new $this->rowClass($data, $this);
			}
		} elseif ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) {
			if ($res = $sql->execute()->fetchAll()) {
				foreach ($res as $key => $data) {
					$rows[] = new $this->rowClass($data, $this);
				}
			}
		} else {
			throw new \Exception("Unexpected object: ".get_class($sql));
		}
		return $rows;
	}

	public function delete(array $where)
	{
		return $this->db->delete($this->table, $where);
	}

	public function query($sql, $bind = array())
	{
		$stmt = $this->db->prepare($sql);
		foreach ($bind as $key => $val) {
			$stmt->bindValue($key, $val);
		}
		return $stmt->execute();
	}

	public function save(Row $row)
	{
		$key = $this->getPrimaryKey();
		if ($row->{$key}) {
			return $this->update($row->getData, array($key => $row->{$key}));
		}
		return $this->insert($row->getData());
	}

	protected function getPrimaryKey()
	{
		return $this->query("SHOW KEYS FROM ? WHERE Key_name = 'PRIMARY'", array($this->table));
	}
}
