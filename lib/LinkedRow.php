<?php

namespace Infinity;

use Ethereal\Db\MetaTableRow;
use Ethereal\Db\RowInterface;
use Ethereal\Db\TableInterface;
use Infinity\Di;
use Infinity\Links;

class LinkedRow extends MetaTableRow implements RowInterface
{
    protected $links = array();
    protected $linksLoaded = false;
    protected $linkNamespace;

    public function __construct($data, TableInterface $table)
    {
        parent::__construct($data, $table);

        if ($this->id) {
            $this->getLinks();
        }
    }

    public function getLinks()
    {
        if (!$this->linksLoaded) {
            foreach (Di::getDi()['links']->getLinks($this->linkNamespace, $this->id) as $link) {
                $this->links[] = $link->getOpposingLink($this->linkNamespace);
            }
        }
        return $this->links;
    }

    public function toArray()
    {
        $data = parent::toArray();
        $links = array();
        foreach ($this->getLinks() as $link) {
            $links[] = array(
                'href' => $link->getUrl(),
                'meta' => array(
                    'title' => $link->getTitle(),
                    'type' => $link->getNamespace(),
                    'id' => $link->getId()
                )
            );
        }
        $data['links'] = $links;
        return $data;
    }
}
