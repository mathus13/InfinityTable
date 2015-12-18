<?php
namespace Infinity\Campaigns;

use Ethereal\Db\Row;
use Ethereal\Db\RowInterface;
use Ethereal\Db\TableInterface;

class Campaign extends \Ethereal\Db\Row implements \Ethereal\Db\RowInterface
{
    public function __construct($data, \Ethereal\Db\TableInterface $table)
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
        $this->disabled_by = 0;
        $this->disabled_date = date('Y-m-d h:i:s');
        $this->save();
    }
}
