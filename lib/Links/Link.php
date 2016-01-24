<?php
namespace Infinity\Links;

use Ethereal\Db\Row;
use Infinity\Di;

class Link extends Row implements RowInterface
{
	private $to_link;
	private $from_link;

	public function __construct($data, TableInterface $table)
	{
		parent::__construct($data, $table);
		$links = Di::getDi()['links'];
		$this->to_link = $links->getLinkObject($this->to, $this->to_id);
		$this->from_link = $links->getLinkObject($this->from, $this->from_id);
	}

	public function getLink($target)
	{
		if ($target === 'to') {
			return $this->to_link;
		}

		if ($target === 'from') {
			return $this->from_link;
		}
	}

	public function getOpposingLink($ns)
	{
		if ($this->to == $ns) {
			return $this->from_link;
		}

		if ($this->from == $ns) {
			return $this->to_link;
		}
	}
}