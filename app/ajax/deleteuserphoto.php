<?php
class deleteuserphoto
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION["bemyguest_user"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once 'app/functions/request.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$parent = functions\request::index("POST","parent");
		$id = functions\request::index("POST","id");

		if(is_numeric($parent) && is_numeric($id)){
			$removephotobyuser = new Database("photos", array(
					"method"=>"removephotobyuser",
					"parent"=>$parent,
					"id"=>$id
				)
			);

			if($removephotobyuser->getter()){
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"ოპერაცია შესრულდა წარმატებით !",
						"Details"=>""
					)
				);
			}
		}

		return $this->out;
	}
}
?>