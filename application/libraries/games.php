<?php
/**
* Character_lib
*
* @uses     
*
* @category Category
* @package  Package
* @author    <>
* @license  
* @link     
*/
class Character_lib{
	public function __construct(){
		// Get CI instance
		$this->ci =& get_instance();
	}

    /**
     * listActive
     * 
     * @param mixed $groups Description.
     *
     * @access public
     *
     * @return mixed .
     */
	public function listActive($groups){
		$games = array();
		foreach($groups as $group){
			$resp = $this->ci->couchdb->key($group)->asArray()->getView('games','by_group');
			$games = array_merge($games,reduce($resp));
		}
		return $games;
	}

    /**
     * newGame
     * 
     * @param mixed $details object containing game properties.
     *
     * @access public
     *
     * @return mixed object is successful on couchdb id and revision.
     */
	public function newGame($details){
		if(!is_object($details){
			$game = new stdClass();
			$game = merge_doc($game,$details);
		}else{
			$game = $details;
		}
		if(!$id = $this->ci->couchdb->storeDoc($game)){
			return false;
		}else{
			return $id;
		}


	}
}