<?php
namespace Infinity\Links;

interface RowInterface
{
    /**
     * get link object of opposing namespace
     * @param  string $ns namespace
     * @return Infinity\Link\LinkableInterface
     */
    public function getOpposingLink($ns);

    /**
     * get link object of passed namespace
     * @param  string $ns namespace
     * @return Infinity\Link\LinkableInterface
     */
    public function getLinkByNS($ns);
}
