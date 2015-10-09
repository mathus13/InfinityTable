<?php
namespace Ethereal;

use DirectoryIterator;
use Ethereal\Cache;

class Config
{
    
    private $config;
    protected $cache;
    protected $dir = __DIR__.'/config/';
    
    public function __construct(Ethereal\Cache $cache)
    {
        $this->cache = $cache;
        if ($this->cache->get())
        if (!is_dir($this->dir)) {
            throw new \Exception('Invalid Config Directory: '.$this->dir);
        }
        $config = array();
        $files = array();
        foreach (new \DirectoryIterator($this->dir) as $file) {
            if (strpos($file->getFilename(), '.json') && $file->isReadable()) {
                $files[$file->getFilename()] = $file->getPathname();
            }
        }
        ksort($files);
        foreach ($files as $name => $path) {
            $h = fopen($path, 'r+');
            $json = fread($h, 2048);
            if ($parse = json_decode($json)) {
                foreach ($parse as $k => $v) {
                    $config[$k] = $v;
                }
            } else {
                throw new \Exception('Invalid Config: '.$json);
            }
        }
        $this->cache->set('Ethereal\Config', $config);
        $this->config = $config;
    }

    public function get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }
}
