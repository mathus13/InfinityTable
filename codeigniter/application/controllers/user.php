<?php
class User extends CI_Controller {
    function index(){
        $this->load->view('login');
    }
    function listUsers(){
        $query = $this->db->query('SELECT * FROM user');
        $data['people'] = $query->result_array();
        $this->load->view('users', $data);
    }
}
?>
