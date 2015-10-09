<?php

namespace Ethereal\Db;

use Ethereal\Db\Table;

class Row
{
	protected $data = array();
	protected $table;

	public function __construct($data, Table $table)
	{
		foreach ($data as $key => $value) {
			$this->data[$key] = $value;
		}
		$this->table = $table;
	}

    /**
     * Gets the value of table.
     *
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * get data attributes
     * @param  string $name data attribute
     * @return        data attribute or NULL
     */
    public function __get($name)
    {
    	if (isset($this->data[$name])) {
    		return $this->data[$name];
    	}
    	return null;
    }

    public function save()
    {
    	$this->table->save($this);
    }

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
