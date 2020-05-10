<?php 
namespace functions; 

class addNewCatalogItemByUser
{
	public $out;
	public function index()
	{
		require_once("app/functions/request.php");
		require_once("app/functions/fileUpload.php");

		if(
			!request::index("POST","title") || 
			!request::index("POST","sale_price") || 
			!request::index("POST","rooms") || 
			!request::index("POST","type") || 
			!request::index("POST","price") || 
			!request::index("POST","description") || 
			!request::index("POST","address")
		){
			$this->out = "Пожалуйста, заполните обязательные поля!";
		}else{
			$catalogId = 2;
			$date = date("d-m-Y");
			$orderid = 1;
			$title = strip_tags(request::index("POST","title")); 
			$sale_price = strip_tags(request::index("POST","sale_price")); 
			$rooms = strip_tags(request::index("POST","rooms")); 
			$type = strip_tags(request::index("POST","type")); 
			$price = strip_tags(request::index("POST","price")); 

			$pricesByday = array();
			$pricesByMonth = array();
			$monthArray = array("jan"=>"Январь", "feb"=>"Февраль", "mar"=>"Март", "apr"=>"Апрель", "may"=>"Май", "jun"=>"Июнь", "jul"=>"Июль", "aug"=>"Август", "sep"=>"Сентябрь", "oct"=>"Октябрь", "nov"=>"Ноябрь", "dec"=>"Декабрь");
			foreach ($monthArray as $key => $value) {
				if(isset($_POST["day{$key}"])){
					$pricesByday[] = sprintf("day%s@@%s", $key, strip_tags($_POST["day{$key}"]));
				}
				if(isset($_POST["month{$key}"])){
					$pricesByMonth[] = sprintf("month%s@@%s,", $key, strip_tags($_POST["month{$key}"]));
				}
			}

			$priceBy = ($sale_price==54) ? $pricesByday : $pricesByMonth;

			$input = array();
			if(isset($_POST["input"]))
			{	
				foreach ($_POST["input"] as $key => $value) {
					$input[] = sprintf("input[%d]=%s", $key, strip_tags($value));
				}
			}

			// $description = strip_tags(request::index("POST","description")); 
			$description = nl2br(htmlentities(request::index("POST","description"), ENT_QUOTES, 'UTF-8'));
			$address = strip_tags(request::index("POST","address")); 
			$mapCords = strip_tags(request::index("POST","mapCords")); 

			$count = count($_FILES['file']['name']);

			$insertFileName = array();
			for($i=0; $i<$count; $i++)
			{
				if(isset($_FILES['file']['name'][$i]) && !empty($_FILES['file']['name'][$i]))
				{
					$fileUpload = new fileUpload();
					$fileUpload->target_dir = sprintf(
						"%spublic/filemanager/userphotos/%s/", 
						\Config::DIR,
						$_SESSION["bemyguest_user"]
					);

					if (!file_exists($fileUpload->target_dir)) {
					    @mkdir($fileUpload->target_dir, 0777, true);
					}

					$fileUpload->file_max_size = 5000000;
					$fileName = $fileUpload->index(array(
						"name"=>$_FILES['file']['name'][$i], 
						"tmp_name"=>$_FILES['file']['tmp_name'][$i], 
						"size"=>$_FILES['file']['size'][$i], 
					));
					if(!empty($fileName)){
						$expl = explode(\Config::DIR, $fileName); 
						$insertFileName[] = "/".$expl[1];
						$this->out = $fileUpload->message;
					}
				}
			}

			$db_user = new \Database("user", array(
				"method"=>"select",
				"email"=>$_SESSION["bemyguest_user"]
			));
			$user = $db_user->getter();

			$Database = new \Database('products', array(
				'method'=>'add', 
				'catalogId'=>$catalogId, 
				'date'=>$date, 
				'orderid'=>$orderid, 
				'title'=>$title, 
				'sale_type'=>$sale_price, 
				'rooms'=>$rooms, 
				'type'=>$type, 
				'price'=>$price, 
				'pricebymonth'=>$priceBy, 
				'additional_data'=>$input, 
				'shortDescription'=>"", 
				'longDescription'=>$description, 
				'address'=>$address, 
				'locations'=>$mapCords, 
				'showwebsite'=>1, 
				'insert_admin'=>$user["id"], 
				'lang'=>$_SESSION["LANG"], 
				'serialPhotos'=>$insertFileName
			));

			require_once("app/functions/redirect.php");
			redirect::url(\Config::WEBSITE."ru/welldone");
		}


		return $this->out;
	}
}