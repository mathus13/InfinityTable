<?php
class User {
	public $user_name;
	public $message;
	private $name;
	private $password;
	private $email;
	private $db;
	private $db_connect;
	private $system_table = 'gb01';
	
	//Connects to the database
	function connect($database){
		$this->db = new Database();
		$this->db->connect();
		$this->db->setDatabase($database);
		$this->db_connect = true;
	}
	
	function register($name,$user_name,$password,$email) {
		if ($this->db_connect != true) {
			$this->connect($this->system_table);
		}
		$this->db->insert('user',array('name','user_name','password','email'),array($name,$user_name,$password,$email));
		if($this->db->ress == true){
			$this->message = $user_name."registered";
		}else{
			$this->message = "there was a problem: ".$this->db->error;
		}
	}
	
	function cred_check($field,$value){
		if ($this->db_connect != true) {
			$this->connect($this -> system_table);
		}
		$this->message='step:';
		$this->db->select('user',$field,'`'.$field.'` = '.$value.'');
		if ($this->db->ress == true && $this->db->numResults > 0) {
			$this->message .= 'The '.$field.' of '.$value.' has been taken';
		}else {
			$this->message .= $field.' is ok!';
		}
	}
	
	function login($user_name,$password) {
		if ($this->db_connect != true) {
			$this->connect($this -> system_table);
		}
		$this->db->select('user','name,id','user_name = \''.$user_name.'\' AND password =\''.$password.'\'');
		if ($this->db->ress == true){
			if ($this->db->numResults > 0) {
				$result = $this->db->getResult();
				$this->message = 'Welcome '.$result['name'];
				$_SESSION['user'] = $result['name'];
				$_SESSION['user_id'] = $result['id'];
				$this->ress = true;
				return true;
			}else{
				$this->message = 'Please check username and password';
				$this->ress = false;
				return false;
			}
		}else{
			$this->message = 'There has been a database issue: '.$this->db->error;
			$this->ress = false;
			return false;
		}
	}
	
	function getMessage(){
		return $this->message;
	}	
	function getRess(){
		return $this->ress;
	}
	
}