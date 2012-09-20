<?php
class Groups{
	public function __construct(){
		$this->couchdb->useDatabase('multiverse');
	}
	public function newGroup($data){
		$char = new stdClass();
		$doc = $this->couch_helper($char,$data);
		$resp = $this->couchdb->storeDoc($doc);
		return $resp;
	}
	public function updateGroup($data){
		$char = $this->couchdb->getDoc($data->_id);
		$doc = $this->couch_helper($char,$data);
		$resp = $this->couchdb->storeDoc($doc);
		return $resp;
	}
	public function addUser($user,$groupId){
		$group = $this->couchdb->getDoc($groupId);
		$group->members[] = $user;
		$this->couchdb->storeDoc($group);
		$data['group'] = $this->tank_auth->getProfile($user,'group');
		$data['group'][]=$groupId;
		$resp = $this->tank_auth->setProfileData($user,$data);
		return $resp->ok;
	}
	public function leaveGroup($user,$groupId){
		$group = $this->couchdb->getDoc($groupId);
		$keys = array_flip($group->members);
		$mem = $keys[$user];
		unset($group->members[$mem]);
		$this->couchdb->storeDoc($group);
		
		$data['group'] = $this->tank_auth->getProfile($user,'group');
		$keys = array_flip($data['group']);
		$gr = $keys[$groupId];
		unset($data['group'][$gr]);
		$resp = $this->tank_auth->setProfileData($user,$data);
		return $resp->ok;
	}
	public function deleteGroup($data){
		$data['state']='deleted';
		return $this->updateGroup($data);
		
	}
	public function getGroupsByUser($user){
		$list = $this->couchdb->key($user)->asArray()->getView('group','user');
		$list = $this->couch_helper->reduce($list);
		return $list;
	}
	public function getGroupsByGame($game){
		$list = $this->couchdb->key($game)->asArray()->getView('group','game');
		$list = $this->couch_helper->reduce($list);
		return $list;
		
	}
	public function deleted(){
		$list = $this->couchdb->asArray()->getView('group','delted');
		$list = $this->couch_helper->reduce($list);
		return $list;
		
	}
	public function publicGroups(){
		$list = $this->couchdb->asArray()->getView('group','public');
		$list = $this->couch_helper->reduce($list);
		return $list;
	}
}
//End of libaries/Groups.php