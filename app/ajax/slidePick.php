<?php 
class slidePick
{
	public $out; 
	
	public function __construct()
	{
		
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/l.php';

		$l = new functions\l();

		$action = functions\request::index("POST","action");
		$active = (int)functions\request::index("POST","active"); //5
		$max = (int)functions\request::index("POST","max"); //5
		$idx = (int)functions\request::index("POST","idx");

		if($action=="prev"){
			if($active<=1){
				$newActive = $max;
			}else{
				$newActive = $active - 1;	
			}			
		}else{
			if($active>=$max){
				$newActive = 1;
			}else{
				$newActive = $active + 1;
			}
		}

		$limits = sprintf(" LIMIT %d, 1", ((int)$newActive)-1);
		$Database = new Database("photos", array(
			"method"=>"selectByParent",
			"idx"=>$idx,
			"lang"=>$_SESSION["LANG"],
			"type"=>"products",
			"limits"=>$limits
		));
		$photo = $Database->getter();
		$path = Config::WEBSITE.$_SESSION["LANG"]."/image/loadimage?f=".base64_encode(Config::WEBSITE_.$photo[0]["path"])."&w=".base64_encode("220")."&h=".base64_encode("220");
		// $path = print_r($photo, true);

		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"",
				"Details"=>"!"
			),
			"Success" => array(
				"Code"=>1, 
				"Text"=>"",
				"Active"=>(int)$newActive,
				"Img"=>$path,
				"limits"=>$limits,
				"Details"=>"!"
			)
		);

		return $this->out;
	}
}