<?php 
class newUser
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
		
		$email = strip_tags(functions\request::index("POST","email"));
		$firstlastname = strip_tags(functions\request::index("POST","firstlastname"));
		$mobile = strip_tags(functions\request::index("POST","mobile"));		
		$password = strip_tags(functions\request::index("POST","password"));
		$comfirmpassword = strip_tags(functions\request::index("POST","comfirmpassword"));
		
		if($firstlastname=="" || $email=="" || $mobile=="" || $password=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Ошибка формата электронной почты!",
					"Details"=>"!"
				)
			);
		}else if($password!==$comfirmpassword){
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пароли не совпадают!",
					"Details"=>"!"
				)
			);
		}else{
			$user = new Database("user", array(
				"method"=>"check_user_exists",
				"username"=>$email
			));

			if($user->getter())
			{
				$this->out = array(
					"Error" => array(
						"Code"=>1, 
						"Text"=>"Пользователь с электронной почтой уже существует!",
						"Details"=>"!"
					)
				);
			}else{
				$Database = new Database("user", array(
					"method"=>"addUser",
					"username"=>$email, 
					"password"=>$password, 
					"firstname"=>$firstlastname, 
					"phone"=>$mobile 
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
				}else{
					$this->out = array(
						"Error" => array(
							"Code"=>1, 
							"Text"=>"При выполнении операции произошла ошибка !",
							"Details"=>""
						)
					);
				}
			}

			
		}
		return $this->out;	
	}
}