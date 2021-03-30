<?php
	class Database {
		private $server;
		private $name;
		private $user;
		private $pass;
		
		public $connection;
		
		public function __construct()
		{
			$this->server = "localhost:3306";
			$this->name = "WS311471_netflix_shows";
			$this->user = "WS311471_admin";
			$this->pass = "Ignition1!";
		}
		
		public function getConnection()
		{
			$this->connection = null;
            try{
                $this->connection = new PDO("mysql:host=$this->server;dbname=$this->name", $this->user, $this->pass,
											array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5, PDO::MYSQL_ATTR_FOUND_ROWS => true));
            }
            catch (PDOException $ex)
            {
                echo "PDO Connection to database failed: " . $ex->getMessage();
            }
            return $this->connection;
		}
	}
?>