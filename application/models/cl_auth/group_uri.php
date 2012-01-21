<?php

/**********************
 * CL Auth
 **********************/

class Group_URI Extends Model {

	function Group_URI()
	{
		parent::Model();

		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_table = $this->_prefix.'group_uri';
	}

	function findURI($uri = array(), $group_id)
	{
		$this->db->where_in('request_uri', $uri);
		$this->db->where('group_id', $group_id);

		return $this->db->get($this->_table);
	}
}

?>