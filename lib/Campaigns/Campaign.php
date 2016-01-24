<?php
namespace Infinity\Campaigns;

use Ethereal\Db\RowInterface;
use Ethereal\Db\TableInterface;
use Infinity\Campaigns\Link;
use Infinity\LinkedRow;
use Infinity\Links\LinkableInterface;

class Campaign extends LinkedRow implements RowInterface, LinkableInterface
{
 
    protected $linkNamespace = 'campaign';

    public function __construct($data, TableInterface $table)
    {
        $data = (array) $data;
        if (!array_key_exists('meta_data', $data) || !isset($data['meta_data'])) {
            $data['meta_data'] = '';
        }
        parent::__construct($data, $table);
        if (!$this->created_date) {
            $this->created_date = date('Y-m-d h:i:s');
        }
        if (!$this->created_by) {
            $this->created_by = 0;
        }
    }

    public function delete()
    {
        $this->active = 0;
        $this->disabled_by = 0;
        $this->disabled_date = date('Y-m-d h:i:s');
        $this->save();
    }

    public function getLinkable()
    {
        return new Link($this->id);
    }
}
