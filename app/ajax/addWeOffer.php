<?php 
class addWeOffer
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

		// $commentId = filter_var(functions\request::index("POST","commentId"), FILTER_SANITIZE_NUMBER_INT);
		$checkindate = 0;
		if(!empty(functions\request::index("POST","checkindate"))){
			$dexp = explode("/", functions\request::index("POST","checkindate")); 
			if(isset($dexp[0]) && isset($dexp[1]) && isset($dexp[2])){
				$str = sprintf("%s/%s/%s", $dexp[1], $dexp[0], $dexp[2]);
				$checkindate = strtotime($str);
			}
		}

		$checkoutdate = 0;
		if(!empty(functions\request::index("POST","checkoutdate"))){
			$dexp = explode("/", functions\request::index("POST","checkoutdate")); 
			if(isset($dexp[0]) && isset($dexp[1]) && isset($dexp[2])){
				$str = sprintf("%s/%s/%s", $dexp[1], $dexp[0], $dexp[2]);
				$checkoutdate = strtotime($str);
			}
		}

		$adults = (int)functions\request::index("POST","adults");
		$children = (int)functions\request::index("POST","children");
		$canpay = strip_tags(functions\request::index("POST","canpay"));		
		$willings = strip_tags(functions\request::index("POST","willings"));
		$firstname = strip_tags(functions\request::index("POST","firstname"));
		$phone = strip_tags(functions\request::index("POST","phone"));
		$email = strip_tags(functions\request::index("POST","email"));

		if($checkindate=="" || $checkoutdate=="" || $firstname=="" || ($phone=="" && $email==""))
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Пожалуйста, заполните обязательные поля!",
					"Details"=>"!"
				)
			);
		}else{
			$Database = new Database("variants", array(
				"method"=>"insert",
				"checkin"=>$checkindate, 
				"checkout"=>$checkoutdate, 
				"adults"=>$adults, 
				"children"=>$children, 
				"canpay"=>$canpay, 
				"willings"=>$willings,
				"firstname"=>$firstname,
				"phone"=>$phone,
				"email"=>$email,
				"lang"=>$_SESSION["LANG"]
			));

			$args["sendTo"] = Config::RECIEVER_EMAIL; 
			$subject = sprintf("Bemyguest.ge %s - %s", date("d/m/Y", $checkindate), date("d/m/Y", $checkoutdate));
			$args["subject"] = $subject;
			$message = "<strong>Варианты</strong>\n";
			$message .= "<p><strong>Дата заезда:</strong> ".date("d-m-Y", $checkindate)."</p>\n";
			$message .= "<p><strong>Дата отъезда:</strong> ".date("d-m-Y", $checkoutdate)."</p>\n";
			$message .= "<p><strong>Колличество человек Взрослые:</strong> ".$adults."</p>\n";
			$message .= "<p><strong>Дети:</strong> ".$children."</p>\n";
			$message .= "<p><strong>Какую максимальную сумму вы можите платить в сутки? ($):</strong> \n".$canpay."</p>";
			$message .= "<p><strong>Ваши пожелания:</strong>\n ".$willings."</p>\n";
			$message .= "<p><strong>Имя:</strong>\n ".$firstname."</p>\n";
			$message .= "<p><strong>Телефон:</strong>\n ".$phone."</p>\n";
			$message .= "<p><strong>Email:</strong>\n ".$email."</p>\n";
			
			$args["body"] = $message; 

			$sendEmail = new functions\sendEmail();
			$sendEmail->index($args);

			if($Database->getter()){
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"Ваша заявка принята в ближайшее время я свяжусь с вами !",
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
		return $this->out;	
	}
}