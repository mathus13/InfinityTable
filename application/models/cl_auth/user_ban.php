<?php

/**********************
 * CL Auth
 **********************/

class User_Ban Extends Model {

	function User_Ban()
	{
		parent::Model();
		
		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_table = $this->_prefix.'user_ban';
	}

	function check($data=array())
	{
		$this->db->or_where($data);
		return $this->db->get($this->_table);
	}
}

?>