<?php

namespace Infinity\Campaigns;

use Clients\Links\LinkInterface;
use Infinity\Campaigns\Campaigns;

class Link implements LinkInterface
{
    private $id;
    private $title;
    private $object;
    private $namespace = 'Infinity\Campaigns\Campaign';

    /**
     * Pass id of the campaign to build link element
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        if (!$this->title) {
            $this->title = $this->getObject()->title;
        }
        return $this->title;
    }

    /**
     * Gets the value of object.
     *
     * @return Infinity\Campaigns\Campain
     */
    public function getObject()
    {
        if (!$this->object) {
            $table = new Campaigns;
            $this->object = $table->find($this->id);
        }
        return $this->object;
    }

    /**
     * Gets the value of namespace.
     *
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getUrl()
    {
        return "/campaigns/{$this->id}";
    }
}
