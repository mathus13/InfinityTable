<?php
namespace Campaign;
require_once APPPATH.'/classes/controller/master.php';

class Controller_Campaign extends \App\Controller_Master {
	
	private $_config;
	
	public function before() {
		parent::before();
		\Config::load('Campaign::menu.json','menu');
		$this->template->subnav = \Config::get('menu',array());
	}

	public function action_list() {
		$model = new Model_Campaign;
		$list = $model->find();
		$this->_data['campaigns'] = ($list)? $list : array();
		$this->template->nav = array('list'=> 'active' );
		$this->template->title = 'Index &raquo; List';
		$this->template->content = \View::forge('index/list', $this->_data);
	}

	public function action_edit() {
		$this->template->nav = array('edit'=> 'active' );
		$this->template->title = 'Index &raquo; Edit';
		$this->template->content = \View::forge('index/edit', $this->_data);
	}

	public function action_add() {
		$this->template->nav = array('add'=> 'active' );
		$this->template->title = 'Index &raquo; Add';
		$this->template->content = \View::forge('index/add', $this->_data);
	}

	public function action_delete()
	{
		$this->template->nav = array('delete'=> 'active' );
		$this->template->title = 'Index &raquo; Delete';
		$this->template->content = \View::forge('index/delete', $this->_data);
	}

}
