<?php

namespace Infinity;

use Ethereal\Db\MetaTableRow;
use Ethereal\Db\RowInterface;
use Ethereal\Db\TableInterface;
use Infinity\Di;
use Infinity\Links;
use Infinity\Links\Exception;

class LinkedRow extends MetaTableRow implements RowInterface
{
    protected $links = array();
    protected $linksLoaded = false;
    protected $linkNamespace;
    protected $linkFields = array();

    public function __construct($data, TableInterface $table)
    {
        parent::__construct($data, $table);

        if ($this->id) {
            $this->getLinks();
        }
    }

    public function link($to, $to_id)
    {
        if (!array_key_exists($to, $this->linkFields)) {
            throw new Exception("links to {$to} are no allowed");
        }
        $links = Di::getDi()['links'];
        if ($this->linkFields[$to] == 'belongsTo') {
            if (isset($this->{$to})) {
                throw new Exception("Link to {$to} already exists");
            }
        }
        try {
            $from = $this->getLinkable();
            $from->link($to, $to_id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function unlink($to, $to_id)
    {

    }

    public function getLinks()
    {
        if (!$this->linksLoaded) {
            foreach (Di::getDi()['links']->getLinks($this->linkNamespace, $this->id) as $link) {
                if ($this->linkFields[$link->getNamespace()] == 'belongsTo') {
                    $this->{$link->getNamespace()} = $link;
                } else {
                    if (!$this->{$link->getNamespace()}) {
                        $this->{$link->getNamespace()} = array();
                    }
                    $this->{$link->getNamespace()}[$link->getId()] = array();
                }
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
