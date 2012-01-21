<?php

/**********************
 * CL Auth
 **********************/

class Groups Extends Model {

	function Groups()
	{
		parent::Model();
		
		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_table = $this->_prefix.'groups';
	}

	function getAll()
	{
		$this->db->order_by('id', 'asc');
		return $this->db->get($this->_table);
	}
}

?>