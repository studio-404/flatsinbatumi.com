<?php 
class recoverPassword2
{
	public $out; 
	
	public function __construct()
	{
		
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/send.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"Произошла ошибка !",
				"Details"=>"!"
			)
		);

		
		$updatepassword = strip_tags(functions\request::index("POST","updatepassword"));	
		$newpass = strip_tags(functions\request::index("POST","newpass"));	
		$confirmnewpass = strip_tags(functions\request::index("POST","confirmnewpass"));	
		
		if($updatepassword=="" || $newpass=="" || $confirmnewpass=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else if($newpass!==$confirmnewpass)
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пароли не совпадают!",
					"Details"=>"!"
				)
			);
		}else{
			$updatepasswordWithRecoveryPassword = new Database("user", array(
				"method"=>"updatepasswordWithRecoveryPassword",
				"password"=>$newpass,
				"recoverpassword"=>$updatepassword
			));

			if($updatepasswordWithRecoveryPassword->getter())
			{
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"Операция выполнена успешно!",
						"Details"=>""
					)
				);	
			}else{
				$this->out = array(
					"Error" => array(
						"Code"=>1, 
						"Text"=>"Произошла ошибка",
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