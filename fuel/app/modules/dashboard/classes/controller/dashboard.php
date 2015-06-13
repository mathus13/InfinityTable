<?php
namespace Dashboard;
require_once APPPATH.'/classes/controller/master.php';

class Controller_Dashboard extends \App\Controller_Master {
	
	public function before() {
		parent::before();
			$this->template->subnav = array('links' => array());
	}

	public function action_index() {
		$this->template->nav = array('list'=> 'active' );
		$this->template->title = 'Dashboard';
		$this->template->content = 'hi';
	}
}