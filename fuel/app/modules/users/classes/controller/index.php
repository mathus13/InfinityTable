<?php

class Controller_Index extends Controller_Template
{

	public function action_list()
	{
		$data["subnav"] = array('list'=> 'active' );
		$this->template->title = 'Index &raquo; List';
		$this->template->content = View::forge('index/list', $data);
	}

	public function action_edit()
	{
		$data["subnav"] = array('edit'=> 'active' );
		$this->template->title = 'Index &raquo; Edit';
		$this->template->content = View::forge('index/edit', $data);
	}

	public function action_add()
	{
		$data["subnav"] = array('add'=> 'active' );
		$this->template->title = 'Index &raquo; Add';
		$this->template->content = View::forge('index/add', $data);
	}

	public function action_delete()
	{
		$data["subnav"] = array('delete'=> 'active' );
		$this->template->title = 'Index &raquo; Delete';
		$this->template->content = View::forge('index/delete', $data);
	}

	public function action_friend()
	{
		$data["subnav"] = array('friend'=> 'active' );
		$this->template->title = 'Index &raquo; Friend';
		$this->template->content = View::forge('index/friend', $data);
	}

	public function action_defriend()
	{
		$data["subnav"] = array('defriend'=> 'active' );
		$this->template->title = 'Index &raquo; Defriend';
		$this->template->content = View::forge('index/defriend', $data);
	}

}
