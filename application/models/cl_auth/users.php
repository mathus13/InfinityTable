<?php

/**********************
 * CL Auth
 **********************/

class Users Extends Model {

	function Users()
	{
		parent::Model();

		// Other stuff
		$this->_prefix = $this->config->item('CL_table_prefix');
		$this->_user_table = $this->_prefix.'users';
		$this->_profile_user_table = $this->_prefix.'user_profile';
	}

	function checkBan($user_id)
	{
		$this->db->select('banned');
		$this->db->where('id', $user_id);
		return $this->db->get($this->_user_table);
	}

	function getUserById($user_id)
	{
		$this->db->where('id', $user_id);
		return $this->db->get($this->_user_table);
	}

	function getUserByUsername($username)
	{
		$this->db->where('username', $username);
		return $this->db->get($this->_user_table);
	}

	function checkUsername($username)
	{
		$sql = "SELECT 1 FROM ".$this->_user_table." WHERE username = ?";
		$bind[] = $username;
		return $this->db->query($sql, $bind);
	}

	function checkEmail($email)
	{
		$sql = "SELECT 1 FROM ".$this->_user_table." WHERE email = ?";
		$bind[] = $email;
		return $this->db->query($sql, $bind);
	}


	/*
	 * User Table functions
	 */

	function createUser($data)
	{
		$data['created'] = date('Y-m-d H:i:s', time());
		return $this->db->insert($this->_user_table, $data);
	}

	// Changed from getUsers to getUserField in v0.2
	function getUserField($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('id', $user_id);
		return $this->db->get($this->_user_table);
	}

	function setUser($user_id, $data)
	{
		$this->db->where('id', $user_id);
		return $this->db->update($this->_user_table, $data);
	}


	/*
	 * User Profile functions
	 */

	function createProfile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->_profile_user_table);
	}

	// New in v0.2 [BETA]
	function getProfileField($fields)
	{
		$this->db->select($fields);
		return $this->db->get($this->_profile_user_table);
	}

	// New in v0.2 [BETA]
	function getProfile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_profile_user_table);
	}

	// New in v0.2 [BETA]
	function setProfile($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update($this->_profile_user_table, $data);
	}

	// New in v0.2 [BETA]
	function delProfile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete($this->_profile_user_table);
	}

	/*
	 * Login Functions
	 */

	function getLogin($login)
	{
		$this->db->where('username', $login);
		$this->db->or_where('email', $login);
		return $this->db->get($this->_user_table);
	}

	function failedAttempt($username)
	{
		$sql = "UPDATE ".$this->_user_table." SET login_attempts = login_attempts + 1 WHERE username = ? OR email = ?";
		$bind[] = $username;
		$bind[] = $username;
		return $this->db->query($sql, $bind);
	}

	function clearAttempts($username)
	{
		$sql = "UPDATE ".$this->_user_table." SET login_attempts = 0 WHERE username = ?";
		$bind[] = $username;
		return $this->db->query($sql, $bind);
	}

	function newpass($user_id, $pass, $key)
	{
		$data = array(
		'newpass' => $pass,
		'newpass_key' => $key,
		'newpass_time' => date('Y-m-d h:i:s', time() + $this->config->item('CL_forgotten_expire')));

		$this->db->where('id', $user_id);
		return $this->db->update($this->_user_table, $data);
	}

	function activate_newpass($user_id, $key)
	{
		$sql = "UPDATE ". $this->_user_table ." SET password = newpass, newpass = NULL, newpass_key = NULL, newpass_time = NULL WHERE id = ? AND newpass_key = ?";
		$bind[] = $user_id;
		$bind[] = $key;
		return $this->db->query($sql, $bind);
	}

	function clear_newpass($user_id)
	{
		$data = array(
		'newpass' => null,
		'newpass_key' => null,
		'newpass_time' => null);

		$this->db->where('id', $user_id);
		return $this->db->update($this->_user_table, $data);
	}

	function change_password($new_pass, $user_id)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);
		return $this->db->update($this->_user_table);
	}

	// Used in Session class only
	function update_activity($user_id, $active_time)
	{
		$sql = "UPDATE ". $this->_user_table ." SET active_time = active_time + ?, last_visit = NOW() WHERE id = ?";
		$bind[] = $active_time;
		$bind[] = $user_id;
		return $this->db->query($sql, $bind);
	}
}

?>