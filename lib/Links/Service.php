<?php
namespace Infinity\Links;

class Service
{
	private $table;
   private $types;
  
   public function __construct(\Ethereal\Db $db, \Ethereal\Hooks $hooks)
   {
   	$this->table = new Table($db);
      $link_types = $hooks->fire('Infinity\links\init', array());
      $this->types = $link_types;
   }
}