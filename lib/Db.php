<?php 
namespace Ethereal;

use Doctrine\Common;
use Doctrine\DBAL;
use Ethereal\Config as Config;

class Db
{
    
    private $config;
    private $Db;
    
    public function __construct(Config $Config)
    {
        $db_conf = $Config->get('db');
        if (!$db_conf) {
            throw new \Exception('No db configuration set');
        }
        $this->config = $db_conf;
        $this->loadDb();
    }
    
    private function loadDb()
    {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = (array) $this->config;
        try {
            $this->Db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            if (!$this->Db->isConnected() && !$this->Db->connect()) {
                throw new \Exception('Shits fucked up');
            }
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception("{$e->getMessage()}");
        }
    }
    
    /**
     * Pass thru method calls to DBAL instance
     * @TODO Fix this lazy bullshit
     */
    public function __call($method, $params)
    {
        if (method_exists($this->Db, $method)) {
            try {
                return call_user_func_array(array($this->Db, $method), $params);
            } catch (\Exception $e) {
                
            }
        } else {
            throw new \Exception("Method {$method} does not exist in DB");
        }
    }
}
