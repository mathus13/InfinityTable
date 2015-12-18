<?php
namespace Infinity\Users;

use Ethereal\Db\MetaTableRow;
use Ethereal\Db\RowInterface;
use Ethereal\Db\TableInterface;

class User extends MetaTableRow implements RowInterface
{
    public function __construct($data, TableInterface $table)
    {
        if (isset($data['meta_data'])) {
            foreach (explode('||', $data['meta_data']) as $meta) {
                if (strpos($meta, '::') === false) {
                    continue;
                }
                list($key, $value) = explode('::', $meta);
                $data[$key] = $value;
            }
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
        $this->save();
    }

    public function ban($reason)
    {
        $this->banned = 1;
        $this->ban_reason = $reason;
        $this->save();
    }

    public function authenticate($password)
    {
        return password_verify($password, $this->password);
    }
}
