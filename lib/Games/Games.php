<?php
namespace Infinity\Games;

use Infinity\Games\Game;
use Ethereal\Db\MetaTable;

class Games extends MetaTable implements \Ethereal\Db\TableInterface
{
    protected $table = 'game';
    protected $meta_table = 'game_md';
    protected $rowClass = 'Infinity\Games\Game';
    protected $columns = array(
        'id',
        'title',
        'created_date',
        'created_by',
        'disabled_date',
        'disabled_by',
        'active'
    );
}
