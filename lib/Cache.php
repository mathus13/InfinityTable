<?php

namespace Ethereal;

use Predis\Client;

class Cache extends Predis\Client
{
  protected $namespace = '';
  
	public function set($key, $val, $exp = null)
	{
      if (!is_string($key)) {
        throw new \Exception("Key must be a string");
      }
      $key = "{$this->namespace}{$key}";
		if (!is_string($val)) {
			$val = json_encode($val);
		}
		if ($exp && is_numeric($exp)) {
			return parent::set($key, $val, "EX {$exp}");
		}
		return parent::set($key, $val);
	}
  
  public function get($key)
  {
      if (!is_string($key)) {
        throw new \Exception("Key must be a string");
      }
      $key = "{$this->namespace}{$key}";
    $value = parent::get($key);
    if (is_null($value)) {
      return null;
    }
    if ($decode = json_decode($value)) {
      return $decode;
    }
    return $value;
  }
  
  public function setNamespace($namespace)
  {
    $this->namespace = "{$namespace}\";
  }
}
