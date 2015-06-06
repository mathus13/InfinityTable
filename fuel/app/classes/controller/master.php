<?php
namespace App;
use Fuel\Core\Controller_Template;
use Fuel\Core\View;

class Controller_Master extends Controller_Template {
	public $template;
	protected $_data = array();
	
	public function __construct($request) {
		parent::__construct($request);
		$this->template = APPPATH.'views/layout.php';
	}
}