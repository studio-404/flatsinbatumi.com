<?php 
class comments
{
	private $conn;

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if(method_exists("comments", $args['method']))
		{
			$out = $this->$args['method']($args);
		}
		return $out;
	}

	private function removeComments($args)
	{
		$update = "UPDATE `comments` SET `status`=:one WHERE `id`=:id";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":id"=>$args['id'],
			":one"=>1
		)); 
		if($prepare->rowCount())
		{
			return 1;
		}
		return 0;
	}

	private function selectById($args)
	{
		$fetch = array();
		$select = "SELECT * FROM `comments` WHERE `id`=:id AND `status`!=:one";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":id"=>$args['id'],
			":one"=>1 
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
			if($fetch[0]["read"]==0 && !isset($args['noUpdateRead'])){
				$this->updateRead($fetch[0]["id"]);
			}
		}
		return $fetch;
	}

	private function updateRead($id)
	{
		$update = "UPDATE `comments` SET `read`=:one WHERE `id`=:id";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":id"=>$id, 
			":one"=>1
		)); 
		if($prepare->rowCount())
		{
			return 1;
		}
		return 0;
	}

	private function select($args)
	{
		$fetch = array();
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (($_GET['pn']-1)*$itemPerPage) : 0;
		$select = "SELECT (SELECT COUNT(`id`) FROM `comments` WHERE `status`!=:one) as counted, `comments`.* FROM `comments` WHERE `status`!=:one ORDER BY `date` DESC LIMIT ".$from.",".$itemPerPage;
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":one"=>1
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function select_website($args)
	{
		$fetch = array();
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (($_GET['pn']-1)*$itemPerPage) : 0;
		$select = "SELECT (SELECT COUNT(`id`) FROM `comments` WHERE `status`!=:one) as counted, `comments`.* FROM `comments` WHERE `visibility`!=:one AND `status`!=:one ORDER BY `date` DESC LIMIT ".$from.",".$itemPerPage;
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":one"=>1
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function insert($args)
	{
		require_once 'app/functions/server.php';
		$server = new functions\server();
		$ip = $server->ip();
		$out = 0;
		
		try{
			$insert = "INSERT INTO `comments` SET 
			`date`=:datex, 
			`ip`=:ip, 
			`firstname`=:firstname, 
			`photo`=:photo,
			`comment`=:comment, 
			`lang`=:lang, 			
			`read`=:zero, 
			`visibility`=:one, 
			`status`=:zero 
			";
			$prepare = $this->conn->prepare($insert);
			$prepare->execute(array(
				":datex"=>time(),
				":ip"=>$ip,
				":firstname"=>$args['firstname'],
				":photo"=>$args['photo'],
				":comment"=>$args['comment'],
				":lang"=>$args['lang'],
				":zero"=>0,
				":one"=>1
			));	
			if($prepare->rowCount()){
				$out = 1;	
			}			
		}catch(Exception $e){ $out = 0;	}
		return $out;
	}

	private function updateVisibility($args)
	{
		$visibility = ($args['visibility']==0) ? 1 : 0;
		$id = (int)$args['id'];

		$update = "UPDATE `comments` SET `visibility`=:visibility WHERE `id`=:id";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":visibility"=>$visibility, 
			":id"=>$id
		));
		if($prepare->rowCount())
		{
			return 1;
		}
		return 0;
	}
}