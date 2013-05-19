<?php
class Character_model extends CI_Model{

  private $table; 


  function __construct(){
    $this->config->load('characters');
    $this->table = $this->config->item('table');
  }
  public function newCharacter($data){
    $char = new stdClass();
    $doc  = merge_doc($char,$data);
    $resp = $this->db->insert($doc,$this->table);
    return $resp;
  }
  public function updateCharacter($data){
    $char = $this->db->where('id',$data['id'])->get($this->table);
    $char = $char[0];
    $doc  = merge_doc($char,$data);
    $resp = $this->db->where('id',$doc->id)->update($this->table,$doc);
    return $resp;
  }
  public function deleteCharacter($data){
    $data['closed']      = 1;
    $data['closed_date'] = date('c');
    if($this->db->select()->where('id',$data['id'])->update($this->table,$data));
    return $this->updateCharacter($data);
    
  }
  public function getCharactersByUser($user){
    $list = $this->db->select()->where('user',$user)->get($this->table);
    return $list;
  }
  public function getCharactersByGame($game){
    $list = $this->db->select()->where('game',$game)->get($this->table);
    return $list;
    
  }
  public function deleted(){
    $list = $this->db->select()->where('closed',1)->get($this->table);
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