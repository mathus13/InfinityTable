<?php
namespace Ethereal;

use DirectoryIterator;

class Config {
    
    private $_config;
    protected $_dir = 'config';
    
    public function __construct() {
        $config = array();
        foreach (new DirectoryIterator($this->_dir) as $file) {
            if( strpos($file->getFilename(), '.json') && $file->isReadable()) {
                $h = \fopen($file->getPathname());
                $json = fread($h);
                if ($json = json_decode($json,true)) {
                   foreach($json as $k => $v) {
                       $config[$k] = $v;
                   } 
                }
            }
        }
        $this->_config = $config;
    }
}