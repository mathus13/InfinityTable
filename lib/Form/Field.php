<?php 

namespace Infinity\Form;

/**
 * abstract for form field rendering
 * author: Shawn Barratt
 */
abstract class Field
{
	protected $label;
	protected $name;
	protected $help;
	protected $class;
    protected $value;

	public function __contruct(array $data = array())
	{
		foreach ($data as $k => $v) {
			$method = "set".ucfirst($k);
			if (method_exists($this, $method)) {
				$this->{$method}($v);
			}
		}
	}

    /**
     * Gets the value of label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the value of label.
     *
     * @param mixed $label the label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of help.
     *
     * @return mixed
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Sets the value of help.
     *
     * @param mixed $help the help
     *
     * @return self
     */
    public function setHelp($help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * Gets the value of class.
     *
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the value of class.
     *
     * @param mixed $class the class
     *
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of value.
     *
     * @param mixed $value the value
     *
     * @return self
     */
    protected function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}