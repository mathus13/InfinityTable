<?php
namespace Infinity\Campaigns;

use Infinity\Campaigns\Campaign;
use Infinity\MetaTable as MetaTable;

class Clients extends MetaTable implements \Ethereal\Db\TableInterface
{
    protected $table = 'campaign';
    protected $meta_table = 'campaign_md';
    protected $rowClass = 'Infinity\Campaigns\Campaign';
    private $columns = array(
        'id',
        'title',
        'created_date',
        'created_by',
        'disabled_date',
        'disabled_by',
        'active'
    );

    public function getAllActive()
    {
        return $this->fetchAll(
            "SELECT campaign.*, GROUP_CONCAT(md_name,'::',md_value separator '||') AS meta_data 
            FROM {$this->table} campaign LEFT JOIN {$this->meta_table} md  ON ref_id = client.id 
            WHERE `active` = 1 GROUP BY client.id ORDER BY created_date"
        );
    }
}
