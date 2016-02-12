<?php
namespace Infinity\Links;

use Ethereal\Db\Row as DBRow;
use Infinity\Di;
use Infinity\Links\Exception;
use Infinity\Links\RowInterface;

class Row extends DBRow implements RowInterface
{
    private $links;

    public function __construct($data, TableInterface $table)
    {
        parent::__construct($data, $table);
        $this->links = Di::getDi()->get('links');
    }
    
    public function getOpposingLink($ns)
    {
        if ($this->from == $ns) {
            return $this->links->getLinkObject($this->to, $this->to_id);
        } elseif ($this->to == $ns) {
            return $this->links->getLinkObject($this->from, $this->from_id);
        }
        throw new Exception('namespace not found');
    }

    public function getLinkByNS($ns)
    {
        if ($this->to == $ns) {
            return $this->links->getLinkObject($this->to, $this->to_id);
        } elseif ($this->from == $ns) {
            return $this->links->getLinkObject($this->from, $this->from_id);
        }
        throw new Exception('namespace not found');
    }
}
