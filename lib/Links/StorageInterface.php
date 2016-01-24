<?php
namespace Infinity\Links;

interface StorageInterface
{
    public function getLinks($namespace, $id, $opposing);

    public function setLink(LinkInterface $from, LinkInterface $to);
}
