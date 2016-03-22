<?php
namespace Infinity\Form;

class Container
{
	protected $children = array();

	/**
	 * title
	 * @var str
	 */

	protected $title;

	/**
	 * help
	 * @var str
	 */

	protected $help;

	public function __construct($data = array())
	{
		foreach ($data as $k => $v) {
			$method = "set".ucfirst($k);
			if (method_exists($this, $method)) {
				$this->{$method}($v);
			}
		}
	}

	/**
	 * setTitle
	 * @param str $value
	 * @return void
	 */
	public function setTitle(str $value)
	{
		$this->title = $value;
	}
	/**
	 * getTitle
	 * @return str
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * setHelp
	 * @param str $value
	 * @return void
	 */
	public function setHelp(str $value)
	{
		$this->help = $value;
	}
	/**
	 * getHelp
	 * @return str
	 */
	public function getHelp()
	{
		return $this->help;
	}
}