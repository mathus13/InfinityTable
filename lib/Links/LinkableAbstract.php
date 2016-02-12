<?php
namespace Infinity\Links;

abstract class LinkableAbstract
{
    private $linkFields = array();

    public function link($to, $to_id)
    {
        try {
            $links = Infinity\Di::getDi()->get('links');
            $toLink = $links->getLinkObject($to, $to_id);
            $links->link($this, $toLink);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function unlink($to, $to_id)
    {
        try {
            $links = Infinity\Di::getDi()->get('links');
            $toLink = $links->getLinkObject($to, $to_id);
            $links->removeLink($this, $toLink);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
