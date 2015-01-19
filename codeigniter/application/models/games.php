<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Games extends CI_Model{
    
    public static function listOpenGames(){
        $games = new self;
        return $games->_listGames(true,false);
    }

    public static function newGame($name){
        
    }
    
    private $_table;
    private $_md_table;
    private $_headers;
    
    private $id;
    private $_data = array();
    private $state;
    
    public function __construct($id = null){
        parent::__construct();
        if($id){
            $this->loadData($id);
        }
        $this->_headers = $this->db->list_fields($this->_table);
    }
    
    private function loadData($id){
        $list = $this->db->getWhere($this->_table,array('id'=>$id));
        if(count($list) < 1){
            die();
        }
        $game = $list[0];
        foreach($this->db->getWhere($this->_md_table,array('ref_id' => $id)) as $md){
            $game[$md->md_name] = $md->md_value;
        }
        $data = array();
        foreach($game as $f => $v){
            switch($f){
                case 'players':
                    $v = json_decode($v);
                    break;
                case 'id':
                    $this->id = $v;
                    break;
                case 'state':
                    $this->state = $v;
                    break;
                
            }
            $data[$f] = $v;
        }
        $this->_data = $data;
    }
    
    public function get($attr){
        
    }
    
    public function set($attr,$data){
        
    }
    
    public function addPlayer($id){
        if(!is_numeric($id)){
            show_error('Improper ID passed',500);
        }
        $players = $this->get('players');
        $players[] = $id;
        $this->set('players',$players);
        $this->_save();
        
    }
    
    public function removePLayer($id){
        $key = array_search($id,$this->data['players']);
        $players = $this->get('players');
        unset($players[$key]);
        $this->set('players',$players);
    }
    
    public function getPLayers(){
        return $this->players;
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
    
    private function _listGames($open = false, $user = true){
        $where = array('active' => 1);
        if($open){
            $where['status'] = 'open';
        }
        if($user){
            $where['st'] = $this->bitauth->user_id;
        }
        $list = $this->db->select('id')->getWhere($this->_table,$where)->result(self);
        return $list;
    }
    
    private function _save(){
        foreach
    }
    
}
?>