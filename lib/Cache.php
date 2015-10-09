<?php

namespace Ethereal;

use Predis\Client;

class Cache extends Predis\Client
{
	public function set($key, $val, $exp = null)
	{
		if (!is_string($val)) {
			$val = json_encode($val);
		}
		if ($exp && is_numeric($exp)) {
			return parent::set($key, $val, "EX {$exp}");
		}
		return parent::set($key, $val);
	}
}
