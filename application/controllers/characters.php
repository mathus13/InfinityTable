<?php
class Characters extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		if (!$this->tank_auth->is_logged_in()) {
			$this->session->set_usserdata('redir',current_url());
			redirect('/auth/login/');
		}
		$this->load->library('character');
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
    function new_character(){
    	if($form = $this->input->post()){
    		unset($form['submit']);
    		$id = $this->character_lib->newCharacter($form);
    		exit(json_decode($id));
    	}
    	$data = array(
    		'user_id' => $this->session->usedata('ba_userid');
		);
		
    }
}
?>