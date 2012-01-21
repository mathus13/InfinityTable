<?php

/**********************
 * CL Auth
 **********************/

class User_Temp Extends Model {

	function User_Temp()
	{
		parent::Model();

		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_table = $this->_prefix.'user_temp';
	}

	function getLogin($login)
	{
		$this->db->where('username', $login);
		$this->db->or_where('email', $login);
		return $this->db->get($this->_table);
	}

	function checkUsername($username)
	{
		$sql = "SELECT 1 FROM ".$this->_table." WHERE username = ?";
		$bind[] = $username;
		return $this->db->query($sql, $bind);
	}

	function checkEmail($email)
	{
		$sql = "SELECT 1 FROM ".$this->_table." WHERE email = ?";
		$bind[] = $email;
		return $this->db->query($sql, $bind);
	}

	function activateUser($username, $key)
	{
		$this->db->where(array('username_clean' => $username, 'activation_key' => $key));
		return $this->db->get($this->_table);
	}

	function deleteUser($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->_table);
	}

	function pruneTemp()
	{
		$this->db->where('UNIX_TIMESTAMP(created) <', time()-$this->config->item('CL_temp_expire'));
		return $this->db->delete($this->_table);
	}

	function createTemp($data)
	{
		$data['created'] = date('Y-m-d H:i:s', time());
		return $this->db->insert($this->_table, $data);
	}
}

?>