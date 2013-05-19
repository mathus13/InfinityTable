<?php
class Characters extends CI_Controller {
	function __construct(){
		parent::__construct();
		if (!$this->bitauth->logged_in()) {
			$this->session->set_userdata('redir',current_url());
			redirect('/auth/login/');
		}
		$this->load->library('character_lib');
        $this->load->model('character_model');
        $this->assets['css'][] ='vader/jquery-ui-1.8.5.custom.css';
        $this->assets['css'][] ='test.css';
        $this->assets['js'][]  ='jquery.js';
        $this->assets['js'][]  ='jquery-ui-1.8.5.custom.min.js';
    
	}
    function index(){
        $user = $_SESSION['id'];
        $data['character_list'] = $this->character_lib->getCharactersByUser($user);
        $this->load->view('character_list',$data);
    }
    function create(){
    	if($form = $this->input->post()){
    		unset($form['submit']);
    		$id = $this->character_lib->newCharacter($form);
    		exit(json_decode($id));
    	}
    	$data = array(
    		'user_id' => $this->session->userdata('ba_userid')
		);
        $data['content'] = $this->load->view('character_form',$data,true);
        $data['head']    = $this->assets;
        $this->load->view('includes/index',$data);
		
    }
}
?>