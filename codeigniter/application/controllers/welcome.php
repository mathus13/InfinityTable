<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    if (!$this->bitauth->logged_in()) {
      redirect('/auth/login/');
      
        }
    $this->load->model('character');
  }

  function index()
  {
    $this->assets['css'][]='vader/jquery-ui-1.8.5.custom.css';
    $this->assets['css'][]='test.css';
    $this->assets['js'][]='jquery.js';
    $this->assets['js'][]='jquery-ui-1.8.5.custom.min.js';
    //echo '<pre>'.htmlspecialchars(var_export($this->session->all_userdata(),true)).'</pre>';
    $data['head'] = $this->assets;
    $data['user_id']  = $this->session->userdata('ba_user_id');
    $data['characters'] = $this->character->getCharactersByUser($data['user_id']);
    $data['profile'] = $this->session->all_userdata();
    $data['content'] = 'welcome';
    $this->load->view('includes/index', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */