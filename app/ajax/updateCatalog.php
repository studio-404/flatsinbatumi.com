<?php 
class updateCatalog
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
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			),
			"Success" => array(
				"Code"=>0, 
				"Text"=>"",
				"Details"=>"!"
			)
		);

		$idx = filter_var(functions\request::index("POST","idx"), FILTER_SANITIZE_NUMBER_INT);
		$date = functions\request::index("POST","date");
		$orderid = functions\request::index("POST","orderid");
		$title = functions\request::index("POST","title");
		$saleType = functions\request::index("POST","saleType");
		$cities = functions\request::index("POST","cities");
		$rooms = functions\request::index("POST","rooms");
		$type = functions\request::index("POST","type");
		$price = functions\request::index("POST","price");

		$pricebymonth = unserialize(functions\request::index("POST","serialPricebymonthtable"));
		$additional_data = unserialize(functions\request::index("POST","serialAdditionalinfotable"));

		$shortDescription = functions\request::index("POST","shortDescription");
		$longDescription = functions\request::index("POST","longDescription");
		$address = functions\request::index("POST","address");
		$locations = functions\request::index("POST","locations");
		$choosevisibiliti = functions\request::index("POST","choosevisibiliti");
		$lang = functions\request::index("POST","lang");

		$serialPhotos = unserialize(functions\request::index("POST","serialPhotos"));

		$Database = new Database('products', array(
			'method'=>'edit', 
			'idx'=>$idx, 
			'date'=>$date, 
			'orderid'=>$orderid, 
			'title'=>$title, 
			'sale_type'=>$saleType, 
			'cities'=>$cities, 
			'rooms'=>$rooms, 
			'type'=>$type, 
			'price'=>$price, 
			'pricebymonth'=>$pricebymonth, 
			'additional_data'=>$additional_data, 
			'shortDescription'=>$shortDescription, 
			'longDescription'=>$longDescription, 
			'address'=>$address, 
			'locations'=>$locations, 
			'showwebsite'=>$choosevisibiliti, 
			'lang'=>$lang, 
			'serialPhotos'=>$serialPhotos
		));

		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"",
				"Details"=>""
			),
			"Success"=>array(
				"Code"=>1, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			)
		);

		return $this->out;
	}
}