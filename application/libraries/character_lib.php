<?php
class Character_lib{
	public function __construct(){
		// Get CI instance
		$this->ci =& get_instance();
		
		$this->ci->couchdb->useDatabase('multiverse');
	}
	public function newCharacter($data){
		$char = new stdClass();
		$doc = $this->couch_helper($char,$data);
		$resp = $this->ci->couchdb->storeDoc($doc);
		return $resp;
	}
	public function updateCharacter($data){
		$char = $this->ci->couchdb->getDoc($data->_id);
		$doc = $this->ci->couch_helper($char,$data);
		$resp = $this->ci->couchdb->storeDoc($doc);
		return $resp;
	}
	public function deleteCharacter($data){
		$data['state']='deleted';
		return $this->updateCharacter($data);
		
	}
	public function getCharactersByUser($user){
		$list = $this->ci->couchdb->key($user)->asArray()->getView('characters','user');
		$list = reduce($list);
		return $list;
	}
	public function getCharactersByGame($game){
		$list = $this->ci->couchdb->key($game)->asArray()->getView('characters','game');
		$list = reduce($list);
		return $list;
		
	}
	public function deleted(){
		$list = $this->ci->couchdb->asArray()->getView('characters','delted');
		$list = reduce($list);
		return $list;
		
	}
	public function publicCharacters(){
		$list = $this->ci->couchdb->asArray()->getView('characters','public');
		$list = reduce($list);
		return $list;
	}
}
//End of libaries/Characters.php