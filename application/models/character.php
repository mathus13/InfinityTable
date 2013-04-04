<?php
  class Character extends CI_Model{
    function __construct(){
      $this->library->load('couchdb');
   
    
    $this->couchdb->useDatabase('multiverse');
  }
  public function newCharacter($data){
    $char = new stdClass();
    $doc = merge_doc($char,$data);
    $resp = $this->couchdb->storeDoc($doc);
    return $resp;
  }
  public function updateCharacter($data){
    $char = $this->couchdb->getDoc($data->_id);
    $doc = merge_doc($char,$data);
    $resp = $this->couchdb->storeDoc($doc);
    return $resp;
  }
  public function deleteCharacter($data){
    $data['state']='deleted';
    return $this->updateCharacter($data);
    
  }
  public function getCharactersByUser($user){
    $list = $this->couchdb->key($user)->asArray()->getView('characters','user');
    $list = reduce($list);
    return $list;
  }
  public function getCharactersByGame($game){
    $list = $this->couchdb->key($game)->asArray()->getView('characters','game');
    $list =$this->couch_helper reduce($list);
    return $list;
    
  }
  public function deleted(){
    $list = $this->couchdb->asArray()->getView('characters','delted');
    $list = reduce($list);
    return $list;
    
  }
  public function publicCharacters(){
    $list = $this->couchdb->asArray()->getView('characters','public');
    $list = reduce($list);
    return $list;
  }
}
    //end model character
    // application/models/character.php