<?php 
namespace functions;

class addComment
{
	public function __construct()
	{

	}

	public function index()
	{
		require_once("app/functions/request.php");
		$out = "";
		if(
			request::index("POST","usersname") && 
			request::index("POST","comment") && 
			isset($_FILES["uploadbtn"]["name"])
		){
			$check = getimagesize($_FILES["uploadbtn"]["tmp_name"]);

			$imageFileType = strtolower($_FILES["uploadbtn"]["name"]);
			$imageFileType = explode(".", $imageFileType);
			$imageFileType = end($imageFileType);

    		if($check == false)
    		{
    			return "Фатальная ошибка";
    		}

    		if ($_FILES["uploadbtn"]["size"] > 1000000)
    		{
    			return "К сожалению, ваш файл слишком большой.";
    		}

    		if(
    			$imageFileType != "jpg" && 
    			$imageFileType != "png" && 
    			$imageFileType != "jpeg" && 
    			$imageFileType != "gif"
    		){
			    return "К сожалению, разрешены только файлы JPG, JPEG, PNG и GIF.";
			}

			$filename = time().".".$imageFileType;
			$target_file = \Config::DIR . "public/filemanager/comments/".$filename; 

			if (move_uploaded_file($_FILES["uploadbtn"]["tmp_name"], $target_file)) {
				$comments = new \Database("comments", array(
					"method"=>"insert",
					"firstname"=>request::index("POST","usersname"),
					"photo"=>$filename,
					"comment"=>request::index("POST","comment"),
					"lang"=>$_SESSION["LANG"]
				));

		        return "Спасибо за отзыв, Приезжайте к нам ещё.";
		    } else {
		        return "Извините, произошла ошибка";
		    }
			
		}else{
			return "Все поля обязательны для заполнения!";
		}
	}

}