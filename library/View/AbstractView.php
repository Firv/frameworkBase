<?php

abstract class AbstractView  
{
	protected $_params = array();

	public function getAttribute($key)
	{
	    if(isset($this->_params[$key])) {
            return $this->_params[$key];
        }
		return null;
	}
	
	public function setAttribute($key, $value)
	{
		if (isset($this->_params[$key])) {
			throw new Exception('La variable de vista' . $key . ' ya existe');
			return false;
		}

		$this->_params[$key] = $value;
		return $this;
	}
	
    public function __get($key)
    {
		return $this->getAttribute($key);
    }
	
    public function __set($key, $val)
    {
		$this->setAttribute($key, $val);
    }

    public function __isset($key)
    {
        return isset($this->_params[$key]);
    }

    public function __unset($key)
    {
        if (isset($this->_params[$key])) {
            unset($this->_params[$key]);
        }
    }
    
    abstract public function render($viewName);
}
	