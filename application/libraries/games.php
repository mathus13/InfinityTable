<?php
class Character_lib{
	public function __construct(){
		// Get CI instance
		$this->ci =& get_instance();
	}
	public function listActive($groups){
		$games = array();
		foreach($groups as $group){
			$resp = $this->ci->couchdb->key($group)->asArray()->getView('games','by_group');
			$games = array_merge($games,reduce($resp));
		}
		return $games;
	}