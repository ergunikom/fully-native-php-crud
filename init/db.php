<?php

class Database
{
	protected $host = "localhost";
	protected $username = "root";
	protected $password = "";
	protected $database = "test_crud";
	private static $_db;

	function __construct($config = array())
	{
		if(isset($config['host'])) {
			$this->host = $config['host'];
			$this->username = $config['username'];
			$this->password = $config['password'];
			$this->database = $config['database'];
		}

		$this->connect();
	}

	private function connect()
	{
		try {
			self::$_db = new \Pdo("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
			self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			die("Error: ".$e->getMessage());
		}
	}

	public static function db()
	{
		return self::$_db;
	}
}