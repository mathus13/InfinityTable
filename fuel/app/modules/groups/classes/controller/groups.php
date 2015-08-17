<?php
namespace Groups;
require_once APPPATH.'/classes/controller/master.php';

class Controller_Groups extends \App\Controller_Master {

	public function action_list() {
		$data["subnav"] = array('list'=> 'active' );
		$this->template->title = 'Index &raquo; List';
		$this->template->content = View::forge('index/list', $data);
	}

	public function action_edit() {
		$data["subnav"] = array('edit'=> 'active' );
		$this->template->title = 'Index &raquo; Edit';
		$this->template->content = View::forge('index/edit', $data);
	}

	public function action_add() {
		$data["subnav"] = array('add'=> 'active' );
		$this->template->title = 'Index &raquo; Add';
		$this->template->content = View::forge('index/add', $data);
	}

	public function action_delete() {
		$data["subnav"] = array('delete'=> 'active' );
		$this->template->title = 'Index &raquo; Delete';
		$this->template->content = View::forge('index/delete', $data);
	}

	public function action_add_user() {
		$data["subnav"] = array('add_user'=> 'active' );
		$this->template->title = 'Index &raquo; Add user';
		$this->template->content = View::forge('index/add_user', $data);
	}

	public function action_remove_user() {
		$data["subnav"] = array('remove_user'=> 'active' );
		$this->template->title = 'Index &raquo; Remove user';
		$this->template->content = View::forge('index/remove_user', $data);
	}

}
