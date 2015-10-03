<?php 
namespace Ethereal;

use Doctrine\Common;
use Doctrine\DBAL;
use Config;

class Db {
    
    private $_config;
    private $_Db;
    
    public function __construct(Config $Config) {
        $this->_config = $Config->get('db');
        $this->_loadDb();
    }
    
    private static function _loadDb() {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'url' => $this->_config->url,
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }
    
    /**
     * Pass thru method calls to DBAL instance
     * @TODO Fix this lazy bullshit
     */
    public function __get($method, $params) {
        if (method_exists($this->_Db, $method)) {
            try{
                call_user_func_array(array($this->_Db, $method), $params);
            }catch(Exception $e) {
                
            }
        }else{
            throw new Exception("Method {$method} does not exist in DB");
        }
    }
}
