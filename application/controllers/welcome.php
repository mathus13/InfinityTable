<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
	}

	function index()
	{
		$this->assets->load('jquery-ui-1.8.5.custom.css','dark-hive');
		$this->assets->load('test.css');
		$this->assets->load('jquery.js');
		$this->assets->load('jquery-ui-1.8.5.custom.min.js');
		$this->assets->load('global.js');
		$data['head'] = $this->assets->display_header_assets();
		$data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		$query = $this->db->get_where('character',array('userID'=>$data['user_id']));
		$data['characters'] = $query->result_array();
		$query = $this->db->get_where('user_profiles',array('user_id'=>$data['user_id']));
		$data['profile'] = $query->result_array();
		$this->db->where_in('members',$data['user_id']);
		$query = $this->db->get('group');
		$data['groups'] = $query->result_array();
		/*
		$this->db->where_in('members',$data['user_id']);
		$query = $this->db->get('chronical');
		$data['chronicals'] = $query->result_array();
		*/
		$this->load->view('welcome', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */