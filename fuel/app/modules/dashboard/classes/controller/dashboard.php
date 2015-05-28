<?php
namespace Dashboard;
require_once APPPATH.'/classes/controller/master.php';

class Controller_Dashboard extends \App\Controller_Master {

	public function action_index() {
		return new \Response('hi');
	}
}