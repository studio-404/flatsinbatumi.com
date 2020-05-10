<?php 
class Cron extends Controller
{
	public function __construct()
	{

	}

	public function index($name = "")
	{
		// plink -v 185.69.153.30 -l root -pw LlrWnf5UuwXC
		// sudo crontab -e
		// 15 * * * * wget -O - http://bemyguest.ge/ru/currency >/dev/null 2>&1
		// 5 * * * * wget -O - http://bemyguest.ge/ru/cron >/dev/null 2>&1		

		$products = new Database("products", array(
			"method"=>"selectFlatsForCron"
		));

		$fetchAll = $products->getter();
		echo sprintf("<h3>Flats: %d</h3>", count($fetchAll));
		echo "<ul>";
		foreach ($fetchAll as $value) {
			$matches = array();
			if($value["sale_type"]==54){//დღიური ფასი
				$priceDay = explode(",", $value["pricebymonth"]);
				$currentMonth = "day".strtolower(date('M'));
				$pattern = "/".$currentMonth."\@\@(\d+)/";

				foreach ($priceDay as $v) {
					if(preg_match_all($pattern, $v, $matches)){
						break;
					}
				}				
			}

			if($value["sale_type"]==55){//თვის ფასი
				$priceDay = explode(",", $value["pricebymonth"]);
				$currentMonth = "month".strtolower(date('M'));
				$pattern = "/".$currentMonth."\@\@(\d+)/";

				foreach ($priceDay as $v) {
					if(preg_match_all($pattern, $v, $matches)){
						break;
					}
				}				
			}
			
			if(isset($matches[1][0]) && $matches[1][0]>0 && is_numeric($matches[1][0])){
				$products_update = new Database("products", array(
					"method"=>"updateFlatPricesFromCron",
					"price"=>(int)$matches[1][0],
					"idx"=>$value["idx"]
				));
			}
			echo sprintf("<li>%s - %s - id%s</li>", $value["title"], $matches[1][0], $value["idx"]);
		}
		echo "</ul>";

		$productClear = new Database("products", array(
			"method"=>"clearCache"
		));
	}
}