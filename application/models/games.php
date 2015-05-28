<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Games extends CI_Model{
    
    private $_table;
    private $_md_table;
    private $_db;
    
    private $_id;
    private $_name;
    private $_st;
    private $_players = array();
    private $_state;
    
    public static function listOpenGames(){
        
    }

    public function newGame($name){
        
    }
    
    public function addPlayer($id){
        
    }
    
    public function removePLayer($id){
        
    }
    
    public function getPLayers(){
        return $this->_players;
    }
    
    public function changeST(){
        
    }
    
    public function getST(){
        return $this->_st;
    }
    
    public function setState($state){
        
    }
    
    public function getState(){
        return $this->_state;
    }
    
    public function changeName($newName){
        
    }
    
    public function getName(){
        return $this->_name;
    }
    
}
?>