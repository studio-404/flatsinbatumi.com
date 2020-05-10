<?php 
class updateProfileButton
{
	public $out; 
	
	public function __construct()
	{

	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';

		if(!isset($_SESSION["bemyguest_user"]) || $_SESSION["bemyguest_user"]==""){
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"При выполнении операции произошла ошибка !",
					"Details"=>""
				)
			);
			return $this->out;
		}

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"Произошла ошибка !",
				"Details"=>"!"
			)
		);

		
		$firstname = strip_tags(functions\request::index("POST","firstname"));
		$mobile = strip_tags(functions\request::index("POST","mobile"));		
		
		if($firstname=="" || $mobile=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else{
			$user = new Database("user", array(
				"method"=>"check_user_exists",
				"username"=>$_SESSION["bemyguest_user"]
			));

			if($user->getter())
			{
				$Database = new Database("user", array(
					"method"=>"update",
					"firstname"=>$firstname, 
					"lastname"=>"", 
					"dob"=>"2000-01-01", 
					"gender"=>0, 
					"country"=>0, 
					"city"=>0, 
					"phone"=>$mobile, 
					"postcode"=>0 
				));
				if($Database->getter()){
					$this->out = array(
						"Error" => array(
							"Code"=>0, 
							"Text"=>"",
							"Details"=>""
						),
						"Success"=>array(
							"Code"=>1, 
							"Text"=>"Операция прошла успешно !",
							"Details"=>""
						)
					);
				}
			}

			
		}
		return $this->out;	
	}
}