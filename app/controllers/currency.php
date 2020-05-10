<?php
class currency extends Controller
{
	public $out;

	public function index($lang = "", $name = "")
	{
		$html = file_get_contents('http://www.nbg.ge/rss.php');
		$file_path = Config::DIR."public/currency/currency.xml"; 
		$myfile = fopen($file_path, "w+") or die("Unable to open file!");
		fwrite($myfile, (string)$html);
		fclose($myfile);

		$file_get = file_get_contents($file_path);
		$playOnce=1;

		if(preg_match_all("'<description>(.*?)</description>'si", $file_get, $matches)){
			$table = str_replace(array("<description><![CDATA[", "]]></description>"), "", $matches[0][1]);
			
			if(preg_match_all("'<tr>(.*?)</tr>'si", $table, $matches2)){
				foreach ($matches2[0] as $v) {
					if(preg_match_all("'<td>(.*?)</td>'si", $v, $matches3)){
						
						if(isset($matches3[0][0]) && isset($matches3[0][2])){
							$cur = str_replace(array("<td>","</td>"), "", $matches3[0][0]);
							$curVal = str_replace(array("<td>","</td>"), "", $matches3[0][2]);
							
							if($playOnce==1){
								$removeAll = new Database("currencymod", array(
									"method"=>"removeAll"
								));
								$playOnce=500;
							}
							
							$Database = new Database('currencymod', array(
									'method'=>'insert', 
									'name'=>$cur, 
									'value'=>$curVal
							));
						}
					}
				}
			}
		}

		// update all product prices
		$update_prices = new Database('products', array(
			"method"=>"update_prices"
		));
		
	}
}
?>