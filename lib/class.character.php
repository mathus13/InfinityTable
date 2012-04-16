<?php 
/*Class to create / edit / delete characters*/
class Character{
	public $name;
	public $user = $_SESSION['user'];
	private $sql = new Database();
	public $user_name;
	public $message;
	private $name;
	private $password;
	private $email;
	private $db;
	private $db_connect;
	private $system_table = 'gb01';
	
	function connect($database){
		$this->db = new Database();
		$this->db->connect();
		$this->db->setDatabase($database);
		$this->db_connect = true;
	}
	function create($details) {
		
	}
	function submit_ST($st,$character) {
		;
	}
	public function learn($category,$field,$value) {
		;
	}
	public function list_all(){
		
	}
	public function list_one($character){
		
	}

}?>