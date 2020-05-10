<?php 
class photos
{
	private $conn;

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if(method_exists("photos", $args['method']))
		{
			$out = $this->$args['method']($args);
		}
		return $out;
	}

	private function selectByParent($args)
	{
		$limits = (isset($args["limits"])) ? $args["limits"] : '';
		$sql = "SELECT * FROM `photos` WHERE `parent`=:parent AND `lang`=:lang AND `type`=:type AND `status`!=:one ORDER BY `id` ASC".$limits;
		$prepare = $this->conn->prepare($sql);
		$prepare->execute(array(
			":parent"=>$args["idx"], 
			":lang"=>$args["lang"], 
			":type"=>$args["type"], 
			":one"=>1
		));
		if($prepare->rowCount()){
			return $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return array();
	}

	private function removephotobyuser($args)
	{
		$selectProduct = "SELECT 
		(SELECT `users_website`.`email` FROM `users_website` WHERE `users_website`.`id`=`products`.`insert_admin`) AS product_admin_username,
		`products`.`insert_admin` 
		FROM 
		`products` 
		WHERE 
		`idx`=:parent";
		$prepare = $this->conn->prepare($selectProduct);
		$prepare->execute(array(
			":parent"=>$args["parent"] 
		));
		if($prepare->rowCount()){// product selected
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
			if($fetch['product_admin_username']==$_SESSION["bemyguest_user"]){ // product admin checked
				$selectPhoto = "SELECT `id`, `path` FROM `photos` WHERE `id`=:id AND `parent`=:parent";
				$prepare2 = $this->conn->prepare($selectPhoto);
				$prepare2->execute(array(
					":id"=>$args['id'],
					":parent"=>$args['parent']
				));

				if($prepare2->rowCount()){
					$fetch2 = $prepare2->fetch(PDO::FETCH_ASSOC);
					$file = Config::DIR_ . $fetch2['path'];
					if(file_exists($file)){// file exists and deleted try
						@unlink($file);
						// remove photo from database
						$deletePhoto = "DELETE FROM `photos` WHERE `id`=:id";
						$prepare3 = $this->conn->prepare($deletePhoto);
						$prepare3->execute(array(
							":id"=>$fetch2['id']
						));
						return true;
					}
				}
			}
		}

		return false;
	}

	private function deleteByParent($args)
	{
		$delete = "DELETE FROM `photos` WHERE `parent`=:idx AND `type`=:type AND `lang`=:lang";
		$prepareDel = $this->conn->prepare($delete);
		$prepareDel->execute(array(
			":idx"=>$args["idx"], 
			":type"=>$args["type"], 
			":lang"=>$args["lang"]  
		));
		if($prepareDel->rowCount()){
			return 1;
		}
		return 0;
	}

}