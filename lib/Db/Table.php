<?php
/**
 * \Ethereal\Db\Table
 */
namespace Ethereal\Db;

use Row;

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
	protected $rowClass = 'Row';

	public function __construct(Db $db, $table)
	{
		$this->db = $db;
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

	public function select($cols = null)
	{
		return $this->qb()->select($cols)->from($table);
	}

	public function insert(array $data)
	{
		return $this->insert($this->table, $data);
	}

	public function update(array $data, $where = array())
	{
		$update =  $this->qb->update($this->table);
		foreach($data as $k => $v){
			$update->set($k, $v);
		}
		$where_values = array_values($where);
		foreach($where as $stmt => $v) {
			$update->where($statement);
		}
		return $this->db->executeUpdate($update, $where_values);
	}

	public function fetchAll($sql)
	{
		$rows = array();
		foreach($this->db->fetchAll($sql) as $data) {
			$rows[] = new $this->rowClass($data);
		}
		return $rows;
	}

	public function delete(array $where) {
		return $this->db->delete($this->table, $where);
	}

	public function query($sql, $bind = array())
	{
		$stmt = $this->db->prepare($sql);
		foreach($bind as $key => $val){
			$stmt->bindValue($key, $val);
		}
		return $stmt->execute();
	}

}
