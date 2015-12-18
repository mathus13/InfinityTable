<?php
namespace Infinity;

class Utilities
{
    static function loadHookFiles()
    {
        $hooks = array();
        foreach (\DirectoryIterator('.') as $dir) {
            if ($dir->getFilename() == 'hooks.json') {
                //oi
            }
        }
    }
}
