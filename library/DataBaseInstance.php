<?php
final class DataBaseInstance
{
	private $_conn;

	private $_user = "inunoriv_neo";
	private $_password = "inu_bd_rivneo";
	private $_dsn = "mysql:dbname=inunoriv_neo;host=localhost";

	private static $_instance = null;

	static public function getInstance()
	{
		if (!(self::$_instance instanceof DataBaseInstance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		try {
			$this->_conn = new PDO($this->_dsn, $this->_user, $this->_password);
			$this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Problema de coneccion: ' . $e->getMessage();
			throw $e;
		}
	}

	public function getConnection()
	{
		if ($this->_conn === null) {
            self::getInstance();
        }
        
		return $this->_conn;
	}
	
	public function isConnected()
	{
		return ((bool) ($this->_conn instanceof PDO));
	}

	public function closeConnection()
	{
		$this->_conn = null;
	}

	public function __destruct()
	{
		$this->closeConnection();
	}
}