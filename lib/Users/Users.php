<?php
namespace Infinity\Campaigns;

use Ethereal\Db\MetaTable;
use Infinity\Campaigns\Campaign;

class Users extends MetaTable implements \Ethereal\Db\TableInterface
{
    protected $table = 'users';
    protected $meta_table = 'user_md';
    protected $rowClass = 'Infinity\Users\User';
    protected $columns = array(
        'id',
        'email',
        'username',
        'activated',
        'banned',
        'ban_reason',
        'last_ip',
        'last_login',
        'created_date',
        'modified',
        'active'
    );

    public function search(array $params)
    {
        $data = array();
        $search = $this->getSearchQB();
        foreach ($params as $k => $v) {
            switch ($k) {
                case 'id':
                case 'created_date':
                case 'active':
                case 'email':
                case 'username':
                case 'activated':
                case 'banned':
                    $search->where("clients.{$k} = :{$k}");
                    $search->setParameter(":{$k}", $v);
                    break;
                case 'term':
                    $search->where(
                        $this->qb()->expr()->orX(
                            $this->qb()->expr()->like('title', "'%{$v}%'"),
                            $this->qb()->expr()->like('md_value', "'%{$v}%'")
                        )
                    );
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

    public function create($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return parent::create($data);
    }
}
