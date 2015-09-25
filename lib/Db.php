<?php 
namespace Ethereal;

use Doctrine\Common;
use Doctrine\DBAL;
use Config;

class Db {
    
    
    
    public static function getInstance() {
        if (!isset(self::$_connection)) {
            self::_loadDb();
        }
        return self::$_connection;
    }
    
    private static function _loadDb() {
        
    }
}