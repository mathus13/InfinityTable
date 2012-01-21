<?php

/**********************
 * CL Auth
 **********************/

class User_Autologin Extends Model {

	function User_Autologin()
	{
		parent::Model();

		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_user_table = $this->_prefix.'users';
		$this->_table = $this->_prefix.'user_autologin';
		$this->_user_sessions = $this->config->item('sess_table_name');
	}

	function storeKey($data)
	{
		$user = array(
		'key_id' => md5($data['login_id']),
		'user_id' => $data['user_id'],
		'user_agent' => substr($this->input->user_agent(), 0, 149),
		'last_ip' => $this->input->ip_address(),
		'last_login' => time());

		return $this->db->insert($this->_table, $user) ? true : false;
	}

	// See?? Update this function pweez
	function getKey($key, $user_id)
	{
	// captcha ->query($sql, $binds)? Consider revising query
		return $this->db->query("SELECT u.id, u.username, u.group_id  FROM ".
		$this->_user_table ." u, ". $this->_table ." k WHERE ".
		"u.id = '". (int) $user_id ."' AND ".
		"k.user_id = u.id AND ".
		"k.key_id = ". $this->db->escape(md5($key)));
	}

	function delKey($key, $user_id)
	{
		$data = array(
		'key_id' => md5($key),
		'user_id' => $user_id);
		
		$this->db->where($data);
		return $this->db->delete($this->_table);
	}

	function clearKeys($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete($this->_table);
	}

	function clearSessions($user_id)
	{
		$this->db->where('session_user_id', $user_id);
		return $this->db->delete($this->_user_sessions);
	}

	function pruneKeys($user_id)
	{
		$data = array(
		'user_id' => $user_id,
		'user_agent' => substr($this->input->user_agent(), 0, 149),
		'last_ip' => $this->input->ip_address());

		$this->db->where($data);
		return $this->db->delete($this->_table);
	}
}

?>