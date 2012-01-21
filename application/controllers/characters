<?php
class Characters extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}else{
			
			$this->assets->load('jquery-ui-1.8.5.custom.css','dark-hive');
			$this->assets->load('test.css');
			$this->assets->load('jquery.js');
			$this->assets->load('jquery-ui-1.8.5.custom.min.js');
			$this->assets->load('character.js');
		}
	}
    function index(){
        $user = $_SESSION['id'];
        $query = $this->db->query("SELECT * FROM C_data WHERE user_id = '".$user."'");
        if($query->num_rows > 0){
            $data['character_list'] = $query->result();
        }else{
            $data['character_list'] = 0;
        }
        $this->load->view('character_list',$data);
    }
    function new_form(){
	$data['userID'] = $this->tank_auth->get_user_id();
	$this->db->select('groups')->where->('user_id',$data['userID']);
	if($query = $this->db->get('user_profiles')){
            $res = $query->result();
            if(strstr(',',$res)){
               $groups = explode();
            }
        }
	
    }
}
?>