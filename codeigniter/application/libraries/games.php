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
     * get
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function get($id){
        return $this->ci->couchdb->getDoc($id);
    }

    /**
     * save
     * 
     * @param mixed $doc Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function save($doc){
        return $this->save($doc);
    }

    /**
     * updateGame
     * 
     * @param mixed $data Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateGame($data){
        $game = $this->get($data->_id);
        $doc = $this->ci->couch_helper($game,$data);
        $resp = $this->save($doc);
        return $resp;
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
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * deleteGame
     * 
     * @param mixed $game Game ID.
     *
     * @access public
     *
     * @return mixed Database Response.
     */
	public function deleteGame($game){
		$game = $this->ci->couchdb->getDoc($game);
		$game->state = 'deleted';
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * closeGame
     * 
     * @param mixed $game Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function closeGame($game){
		$game = $this->ci->couchdb->getDoc($game);
		$game->state = 'closed';
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * makePublic
     * 
     * @param mixed $game Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function makePublic($game){
		$game = $this->ci->couchdb->getDoc($game);
		$game->open = true;
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * makePrivate
     * 
     * @param mixed $game Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function makePrivate($game){
		$game = $this->ci->couchdb->getDoc($game);
		$game->open = false;
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * joinGame
     * 
     * @param mixed $game Game ID.
     * @param mixed $user User ID.
     *
     * @access public
     *
     * @return mixed CouchDB response.
     */
	public function joinGame($game,$user){
		$game = $this->ci->couchdb->getDoc($game);
		array_push($game->users,$user);
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}

    /**
     * leaveGame
     * 
     * @param mixed $game Game ID.
     * @param mixed $user User ID.
     *
     * @access public
     *
     * @return mixed CouchDB response.
     */
	public function leaveGame($game,$user){
		$game = $this->ci->couchdb->getDoc($game);
		$key = array_search($game->users,$user);
		unset($game->users[$key]);
		$resp = $this->ci->couchdb->storeDoc($game);
		return $resp;
	}
}