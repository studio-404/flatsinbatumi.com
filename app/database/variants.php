<?php 
class variants
{
	private $conn;

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if(method_exists("variants", $args['method']))
		{
			$out = $this->$args['method']($args);
		}
		return $out;
	}

	private function removeVariants($args)
	{
		$update = "UPDATE `variants` SET `status`=:one WHERE `id`=:id";
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
		$select = "SELECT * FROM `variants` WHERE `id`=:id AND `status`!=:one";
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
		$update = "UPDATE `variants` SET `read`=:one WHERE `id`=:id";
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
		$select = "SELECT (SELECT COUNT(`id`) FROM `variants` WHERE `status`!=:one) as counted, `variants`.* FROM `variants` WHERE `status`!=:one ORDER BY `date` DESC LIMIT ".$from.",".$itemPerPage;
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
			$insert = "INSERT INTO `variants` SET 
			`date`=:datex, 
			`ip`=:ip, 
			`checkin`=:checkin, 
			`checkout`=:checkout, 
			`adults`=:adults, 
			`children`=:children, 
			`canpay`=:canpay, 
			`willings`=:willings, 
			`firstname`=:firstname, 
			`phone`=:phone, 
			`email`=:email,
			`lang`=:lang, 			
			`read`=:zero,  
			`status`=:zero 
			";
			$prepare = $this->conn->prepare($insert);
			$prepare->execute(array(
				":datex"=>time(),
				":ip"=>$ip,
				":checkin"=>$args["checkin"],
				":checkout"=>$args["checkout"],
				":adults"=>$args["adults"],
				":children"=>$args["children"],
				":canpay"=>$args["canpay"],
				":willings"=>$args["willings"],
				":firstname"=>$args['firstname'],
				":phone"=>$args['phone'],
				":email"=>$args['email'],
				":lang"=>$args['lang'],
				":zero"=>0
			));	
			if($prepare->rowCount()){
				$out = 1;	
			}			
		}catch(Exception $e){ $out = 0;	}
		return $out;
	}
}