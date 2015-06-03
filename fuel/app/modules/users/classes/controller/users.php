<?php
namespace Users;
require_once APPPATH.'/classes/controller/master.php';

class Controller_Users extends \App\Controller_Master
{

	public function before(){
		parent::before();
		\Config::load('Users::menu.json','menu');
		$this->template->subnav = \Config::get('menu',array());	
	}
	
	public function action_list()
	{
		$this->template->nav = array('list'=> 'active' );
		$this->template->title = 'Index &raquo; List';
		$this->template->content = \View::forge('index/list', $this->_data);
		$this->template->data = $this->_data;
	}

	public function action_edit()
	{
		$this->template->nav = array('edit'=> 'active' );
		$this->template->title = 'Index &raquo; Edit';
		$this->template->content = \View::forge('index/edit', $this->_data);
		$this->template->data = $this->_data;
	}

	public function action_add()
	{
		$this->template->nav = array('add'=> 'active' );
		$this->template->title = 'Index &raquo; Add';
		$this->template->content = \View::forge('index/add', $this->_data);
		$this->template->data = $this->_data;
	}

	public function action_delete()
	{
		$this->template->nav = array('delete'=> 'active' );
		$this->template->title = 'Index &raquo; Delete';
		$this->template->content = \View::forge('index/delete', $this->_data);
		$this->template->data = $this->_data;
	}

	public function action_friend()
	{
		$this->template->nav = array('friend'=> 'active' );
		$this->template->title = 'Index &raquo; Friend';
		$this->template->content = \View::forge('index/friend', $this->_data);
		$this->template->data = $this->_data;
	}

	public function action_defriend()
	{
		$this->template->nav = array('defriend'=> 'active' );
		$this->template->title = 'Index &raquo; Defriend';
		$this->template->content = \View::forge('index/defriend', $this->_data);
		$this->template->data = $this->_data;
	}

}
