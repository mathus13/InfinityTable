<?php
class Characters{
	public function __construct(){
		$this->couchdb->useDatabase('multiverse');
	}
	public function newCharacter($data){
		$char = new stdClass();
		$doc = $this->couch_helper($char,$data);
		$resp = $this->couchdb->storeDoc($doc);
		return $resp;
	}
	public function updateCharacter($data){
		$char = $this->couchdb->getDoc($data->_id);
		$doc = $this->couch_helper($char,$data);
		$resp = $this->couchdb->storeDoc($doc);
		return $resp;
	}
	public function deleteCharacter($data){
		$data['state']='deleted';
		return $this->updateCharacter($data);
		
	}
	public function getCharactersByUser($user){
		$list = $this->couchdb->key($user)->asArray()->getView('character','user');
		$list = $this->couch_helper->reduce($list);
		return $list;
	}
	public function getCharactersByGame($game){
		$list = $this->couchdb->key($game)->asArray()->getView('character','game');
		$list = $this->couch_helper->reduce($list);
		return $list;
		
	}
	public function deleted(){
		$list = $this->couchdb->asArray()->getView('character','delted');
		$list = $this->couch_helper->reduce($list);
		return $list;
		
	}
	public function publicCharacters(){
		$list = $this->couchdb->asArray()->getView('character','public');
		$list = $this->couch_helper->reduce($list);
		return $list;
	}
}
//End of libaries/Characters.php