<?php 
class mysql
{
	public function __construct()
	{

	}

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if(method_exists("mysql", $args['method']))
		{
			$out = $this->$args['method']($args);
		}
		return $out;
	}

	private function executeSqlCom($args)
	{	
		$fetch = array();
		$command = "NOT DETECTED";
		if(preg_match("/SELECT/", $args["command"])){
			
			$select = $args["command"];
			$prepare = $this->conn->prepare($select);
			$prepare->execute();
			if($prepare->rowCount()){
				$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
			}
			return $fetch;

		}

		if(preg_match("/DELETE/", $args["command"])){
			$delete = $args["command"];
			$prepare = $this->conn->prepare($delete);
			$prepare->execute();
			
			return "Effected ".$prepare->rowCount()." rows";
		}

		if(preg_match("/UPDATE/", $args["command"])){
			$update = $args["command"];
			$prepare = $this->conn->prepare($update);
			$prepare->execute();
			
			return "Effected ".$prepare->rowCount()." rows";
		}

		return $command;
	}

	private function tables($args)
	{
		$fetch = array();
		$select = "SHOW TABLES";
		$prepare = $this->conn->prepare($select);
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function colums($args)
	{
		$fetch = array();
		$select = "SHOW COLUMNS FROM `".$args["tablename"]."`";
		$prepare = $this->conn->prepare($select);
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

}