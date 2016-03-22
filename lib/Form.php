<?php
namespace Infinity;

use Infinity\Form\Container;


/**
 * Form
 */

class Form extends Container
{


	/**
	 * action
	 * @var datatype
	 */

	protected $action;

	/**
	 * method
	 * @var datatype
	 */

	protected $method;

	/**
	 * setAction
	 * @param $value
	 * @return void
	 */
	public function setAction($value)
	{
		$this->action = $value;
	}
	/**
	 * getAction
	 * @return $this->action
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * setMethod
	 * @param $value
	 * @return void
	 */
	public function setMethod($value)
	{
		$this->method = $value;
	}
	/**
	 * getMethod
	 * @return $this->method
	 */
	public function getMethod()
	{
		return $this->method;
	}
}