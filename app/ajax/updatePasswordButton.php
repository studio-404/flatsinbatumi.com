<?php 
class updatePasswordButton
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

		
		$current_password = strip_tags(functions\request::index("POST","current_password"));
		$new_password = strip_tags(functions\request::index("POST","new_password"));		
		$confirm_password = strip_tags(functions\request::index("POST","confirm_password"));

		$user = new Database("user", array(
			"method"=>"check",
			"user"=>$_SESSION["bemyguest_user"],
			"pass"=>$current_password
		));		
		
		if($current_password=="" || $new_password=="" || $confirm_password=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else if(!$user->getter())
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"текущий пароль неверен!",
					"Details"=>"!"
				)
			);
		}else if($new_password!=$confirm_password){
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пароли не совпадают!",
					"Details"=>"!"
				)
			);
		}else{
			
			$Database = new Database("user", array(
				"method"=>"updatepassword",
				"username"=>$_SESSION["bemyguest_user"], 
				"password"=>$new_password
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
		return $this->out;	
	}
}