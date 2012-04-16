<?php
class User {
	public $user_name;
	public $message;
	private $name;
	private $password;
	private $email;
	private $db = 'multiverse';
	private $db_connect = 'mdata.iriscouch.com';
	private $system_table = 'gb01';
	require_once "../lib/couch.php";
        require_once "../lib/couchClient.php";
        require_once "../lib/couchDocument.php";
	
        function __construct($couch=null) {
            if(!is_object($couch)){
                $this->message = 'no database object supplied. Default will be used';
                $this->couch = new couchClient($this->db_connect, $this->db);
            }else{
                $this->couch = $couch;
            }
            
        }
	function connect($database){
		$this->db = new Database();
		$this->db->connect();
		$this->db->setDatabase($database);
		$this->db_connect = true;
	}
	
	function register($name,$user_name,$password,$email) {
            $doc = stdClass();
            $doc->name = $name;
            $doc->user_name = $user_name;
            $doc->password = $password;
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