<?php
namespace Infinity\Links;

class Table extends \Ethereal\Db\Table implements \Ethereal\Db\TableInterface, StorageInterface
{

    protected $table = 'links';

    public function getLinks($fromNamespace, $id, $toNamespace = null, $to_id = null)
    {
        $query = $this->select();
        $this->buildExpr($query, $fromNamespace, $id, $toNamespace, $to_id);
        return $this->fetchAll($query);
    }

    public function setLink(LinkInterface $from, LinkInterface $to)
    {
        $this->insert(array(
            'from' => $from->getNamespace(),
            'from_id' => $from->getId(),
            'to' => $to->getNamespace(),
            'to_id' => $to->getId()
        ));
    }

    private function buildExpr(&$query, $ns, $id, $to = null, $to_id = null)
    {
        if ($to && $to_id) {
            $query->where(
                $this->qb()->expr()->andX(
                    $this->qb()->expr()->orX(
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`to`', "'{$ns}'"),
                            $this->qb()->expr()->eq('`to_id`', "'{$id}'")
                        ),
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`from`', "'{$ns}'"),
                            $this->qb()->expr()->eq('`from_id`', "'{$id}'")
                        )
                    ),
                    $this->qb()->expr()->orX(
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`to`', "'{$to}'"),
                            $this->qb()->expr()->eq('`to_id`', "'{$to_id}'")
                        ),
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`from`', "'{$to}'"),
                            $this->qb()->expr()->eq('`from_id`', "'{$to_id}'")
                        )
                    )
                )
            );
        } elseif ($to) {
            $query->where(
                $this->qb()->expr()->andX(
                    $this->qb()->expr()->orX(
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`to`', "'{$ns}'"),
                            $this->qb()->expr()->eq('`to_id`', "'{$id}'")
                        ),
                        $this->qb()->expr()->andX(
                            $this->qb()->expr()->eq('`from`', "'{$ns}'"),
                            $this->qb()->expr()->eq('`from_id`', "'{$id}'")
                        )
                    ),
                    $this->qb()->expr()->orX(
                        $this->qb()->expr()->eq('`to`', "'{$to}'"),
                        $this->qb()->expr()->eq('`from`', "'{$to}'")
                    )
                )
            );
        } else {
            $query->where(
                $this->qb()->expr()->orX(
                    $this->qb()->expr()->andX(
                        $this->qb()->expr()->eq('`to`', "'{$ns}'"),
                        $this->qb()->expr()->eq('`to_id`', "'{$id}'")
                    ),
                    $this->qb()->expr()->andX(
                        $this->qb()->expr()->eq('`from`', "'{$ns}'"),
                        $this->qb()->expr()->eq('`from_id`', "'{$id}'")
                    )
                )
            );
        }
    }
}
