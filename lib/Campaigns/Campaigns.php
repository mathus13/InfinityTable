<?php
namespace Infinity\Campaigns;

use Ethereal\Db\MetaTable;
use Infinity\Campaigns\Campaign;

class Campaigns extends MetaTable implements \Ethereal\Db\TableInterface
{
    protected $table = 'campaigns';
    protected $meta_table = 'campaign_md';
    protected $rowClass = 'Infinity\Campaigns\Campaign';
    protected $columns = array(
        'id',
        'title',
        'created_date',
        'created_by',
        'owner',
        'status',
    );

    public function search(array $params)
    {
        $data = array();
        $search = $this->getSearchQB();
        foreach ($params as $k => $v) {
            switch ($k) {
                case 'term':
                    $search->where(
                        $this->qb()->expr()->orX(
                            $this->qb()->expr()->like('title', "'%{$v}%'"),
                            $this->qb()->expr()->like('md_value', "'%{$v}%'")
                        )
                    );
                    break;
                case 'id':
                case 'created_date':
                case 'active':
                case 'owner':
                case 'created_by':
                    $search->where("clients.{$k} = :{$k}");
                    $search->setParameter(":{$k}", $v);
                    break;
                case 'title':
                    $search->where("title LIKE :title");
                    $search->setParameter(':title', "%{$v}%");
                    break;
                default:
                    $search->where(
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('md_name', "'$k'"),
                            $this->qb()->expr()->like('md_value', "'%{$v}%'")
                        )
                    );
            }
        }
        $search->groupBy('clients.id');
        $search->orderBy('created_date');
        return $this->fetchAll($search);
    }
}