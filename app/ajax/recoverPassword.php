<?php 
class recoverPassword
{
	public $out; 
	
	public function __construct()
	{
		
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/sendEmail.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"Произошла ошибка !",
				"Details"=>"!"
			)
		);

		
		$usersname = strip_tags(functions\request::index("POST","usersname"));	
		
		if($usersname=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else if (!filter_var($usersname, FILTER_VALIDATE_EMAIL))
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Ошибка формата электронной почты!",
					"Details"=>"!"
				)
			);
		}else{
			$user = new Database("user", array(
				"method"=>"check_user_exists",
				"username"=>$usersname
			));

			if($user->getter())
			{
				$recoverpassword = substr(md5(rand(10000,99999)), 5);
				$updaterecover = new Database("user", array(
					"method"=>"updaterecover",
					"pass"=>$recoverpassword, 
					"user"=>$usersname
				));

				$body = "<strong>Код восстановления пароля</strong><br />";
				$body .= sprintf(
					"<h2>%s</h2>", 
					$recoverpassword
				);
				$sendEmail = new functions\sendEmail();
				$sendEmail->index(array(
					"sendTo"=>$usersname,
					"subject"=>"Пароль восстановления - bemyguest.ge",
					"body"=>$body
				));

				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"Пожалуйста, проверьте адрес электронной почты !",
						"Details"=>""
					)
				);
			}else{
				$this->out = array(
					"Error" => array(
						"Code"=>1, 
						"Text"=>"Пользователь с этим адресом электронной почты не существует!",
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