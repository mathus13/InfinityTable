<?php
namespace Infinity;

use Infinity\Di;

class Links
{
    private $storage;
    private $types = array();
    
    public function __construct(Links\StorageInterface $storage)
    {
        $this->storage = $storage;

        // Check for link types in config
        try {
            $link_types = (array) Di::getDi()['config']->get('links');
        } catch (Exception $e) {
            $link_types = array();
        }

        // Check for hook manipulations
        try {
            $link_types = Di::getDi()['hooks']->fire('Infinity\links\init', $link_types);
        } catch (Exception $e) {
            //ignore
        }

        $this->types = $link_types;
    }
    
    /**
     * Pass the fully qualified class name for the source object 
     * and an optional cross-linked obbject
     * ommit the 3rd argument to find all linked objects
     **/
    public function getLinks($namespace, $id, $opposing = null)
    {
        return $this->storage->getLinks($namespace, $id, $opposing);
    }
  
    public function addLink(Links\LinkInterface $from, Links\LinkInterface $to)
    {
        $this->storage->addLink($from->getNamespace(), $from->getId(), $to->getNamespace(), $to->getId());
    }

    public function removeLink(Links\LinkInterface $from, Links\LinkInterface $to)
    {
        $link = $this->storage->getLinks(
            $from->getNamespace(),
            $from->getId(),
            $to->getNamespace(),
            $to->getId()
        );
        if ($link) {
            $link->delete();
        }
    }

    public function getLinkObject($ns, $id)
    {
        if (!array_key_exists($ns, $this->types)) {
            throw new Exception("No link of passed namespace exists", 1);
        }

        $class = $this->types[$ns];
        return new $class($id);
    }
}