<?php 
class updateCommentVisibility
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
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

		$visibility = functions\request::index("POST","visibility");
		$id = functions\request::index("POST","id");

		if($visibility != "" && $id != ""){

			$page = new Database('comments', array(
				"method"=>"updateVisibility",
				"visibility"=>$visibility,
				"id"=>$id
			));
			$result = $page->getter();
			if($result==1){
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success" => array(
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