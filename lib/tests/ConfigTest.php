<?php
namespace Ethereal\Db;

use Ethereal\Cache as Cache;

require_once dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php';

class DbTest extends \PHPUnit_Framework_TestCase
{
	private $cache;
   private $hash;

	public function setup()
	{
		$this->cache = new Cache([
         'scheme' => 'tcp',
         'host'   => 'redis',
         'port'   => 6379,
     ]);
     $this->cache->setNamespace('unittest');
     $this->hash = uniqid();
	}
        
   public function testCacheSetAndGetString()
   {
        $this->cache->set('testkey', $this->hash);
        $val = $this->cache->get('testkey');
        $this->assetTrue($val === $this->hash);
   }
   
   /**
    * @depends testCacheSetAndGetString
    */
   public function testCacheGetAndSetIterable()
   {
        $start = array(
        	'one' => 1,
         'two' => 2
        );
        $start = (object) $start;
        $this->cache->set('test2', $start);
        $end = $this->cache->get('test2');
        $this->assertTrue($start === $end);
   }
   
   /**
    * @depends testCacheSetAndGetString
    */
   public function testCachexpiration()
   {
   	$this->cache->set('test3', 'test', 2);
      sleep(3);
      $this->assertNull($this->cache->get('test3'));
   }
}
