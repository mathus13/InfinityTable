<?php
namespace App;
use Fuel\Core\Controller_Template;
use Fuel\Core\View;

class Controller_Master extends Controller_Template{
	public $template = APPPATH.'views/layout.php';
	protected $_data = array();
}