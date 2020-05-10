<?php 
class loginTry
{
	public $out; 
	
	public function __construct()
	{
		
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"Произошла ошибка !",
				"Details"=>"!"
			)
		);

		
		$usersname = strip_tags(functions\request::index("POST","usersname"));		
		$password = strip_tags(functions\request::index("POST","password"));
		
		if($usersname=="" || $password=="")
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
				"method"=>"check",
				"user"=>$usersname, 
				"pass"=>$password
			));

			if($user->getter())
			{
				$_SESSION["bemyguest_user"] = $usersname;
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"Операция прошла успешно!",
						"Details"=>""
					)
				);
			}else{
				$this->out = array(
					"Error" => array(
						"Code"=>1, 
						"Text"=>"Логин или пароль неверны!",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					)
				);
			}

			
		}
		return $this->out;	
	}
}