<?php 
class loadmoreitems
{
	public $out; 
	
	public function __construct()
	{

	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';
		require_once("app/functions/string.php"); 
		$string = new functions\string(); 

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"Произошла ошибка",
				"Details"=>"!"
			),
			"Success"=>array(
				"Code"=>0, 
				"Text"=>"",
				"results"=>"",
				"loaded"=>"",
				"Details"=>""
			)
		);
		
		$itemPerPage = functions\request::index("POST","itemPerPage"); 
		$from = functions\request::index("POST","from");
		$pn = functions\request::index("POST","pn"); 
		$nextFrom = $from + $itemPerPage;
		$fsale_type = functions\request::index("POST","fsale_type");
		$cities = functions\request::index("POST","cities");
		$frooms = functions\request::index("POST","frooms");
		$ftype = functions\request::index("POST","ftype");
		$fprice_from = functions\request::index("POST","fprice_from");
		$fprice_to = functions\request::index("POST","fprice_to");
		$forderby = functions\request::index("POST","forderby"); 

		if(
			!isset($_SESSION["_GEL_"]) || 
			!isset($_SESSION["_RUB_"]) || 
			!isset($_SESSION["_USD_"])  
		){
			$currencymod = new Database('currencymod', array(
				"method"=>"select"
			));
			$fetchCur = $currencymod->getter();
			$_SESSION["_GEL_"] = 1;
			$_SESSION["_RUB_"] = $fetchCur[0]["value"] / 100;
			$_SESSION["_USD_"] = $fetchCur[1]["value"] ;
		}

		$price_from = "";
		$price_to = "";
		
		if(isset($fprice_from)){
			if($_SESSION['currency']=="gel"){
				$price_from = (float)((int)$fprice_from / (float)$_SESSION["_USD_"]);
			}else if($_SESSION['currency']=="usd"){
				$price_from = (int)$fprice_from;
			}else if($_SESSION['currency']=="rub"){
				$price_from = ((int)$fprice_from * (float)$_SESSION["_RUB_"] / (float)$_SESSION["_USD_"]);
			}
		}

		if(isset($fprice_to)){
			if($_SESSION['currency']=="gel"){
				$price_to = (float)((int)$fprice_to / (float)$_SESSION["_USD_"]);
			}else if($_SESSION['currency']=="usd"){
				$price_to = (int)$fprice_to;
			}else if($_SESSION['currency']=="rub"){
				$price_to = ((int)$fprice_to * (float)$_SESSION["_RUB_"] / (float)$_SESSION["_USD_"]);
			}
		}

		if($from=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"Произошла ошибка",
					"Details"=>"!"
				),
				"Success"=>array(
					"Code"=>0, 
					"Text"=>"",
					"results"=>"",
					"loaded"=>"",
					"Details"=>""
				)
			);
		}else{
			$Database = new Database('products', array(
				'method'=>'select_website', 
				'itemPerPage'=>$itemPerPage, 
				'sale_type'=>$fsale_type, 
				'cities'=>$cities, 
				'rooms'=>$frooms, 
				'type'=>$ftype, 
				'price_from'=>$price_from, 
				'price_to'=>$price_to, 
				'forderby'=>$forderby, 
				'pn'=>$pn, 
				'lang'=>$_SESSION["LANG"]
			));


			if($result = $Database->getter())
			{
				$rsl = "";
				foreach ($result as $item) : 
				// $additionaldata = explode(",", $item["additional_data"]);
    //            	$ploshad_explode = explode("input[35]=", @$additionaldata[0]);
    //             $etaj_explode = explode("input[36]=", @$additionaldata[1]);
    //             $totaletaj_explode = explode("input[93]=", @$additionaldata[2]);
    //             $spalni_explode = explode("input[37]=", @$additionaldata[3]);

				$ploshad_explode = "";
                if(preg_match_all("/input\[35\]\=(\d+)/", $item["additional_data"], $ploshad_explode)){
                  $ploshad_explode = $ploshad_explode[1][0];
                }

                $etaj_explode = "";
                if(preg_match_all("/input\[36\]\=(\d+)/", $item["additional_data"], $etaj_explode)){
                  $etaj_explode = $etaj_explode[1][0];
                }

                $totaletaj_explode = "";
                if(preg_match_all("/input\[93\]\=(\d+)/", $item["additional_data"], $totaletaj_explode)){
                  $totaletaj_explode = $totaletaj_explode[1][0];
                }

                $spalni_explode = "";
                if(preg_match_all("/input\[37\]\=(\d+)/", $item["additional_data"], $spalni_explode)){
                  $spalni_explode = $spalni_explode[1][0];
                } 

				$realprice = 0;
				$curname = "";
				if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="gel"){
					$realprice = (int)($item["price"]*$_SESSION["_USD_"]);
					$curname = "GEL";
				}else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="usd"){
					$realprice = (int)$item["price"];
					$curname = "USD";
				}else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="rub"){
					$realprice = (int)((int)($item["price"]*$_SESSION["_USD_"])/$_SESSION["_RUB_"]);
					$curname = "RUB";
				}


				$rsl .= sprintf(
					"<div class=\"list-group-item\" style=\"background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white\">"
				);
				$rsl .= "<section class=\"row\">";
				$rsl .= "<section class=\"flat-price\" style=\"height:auto;\">"; 
				$rsl .= sprintf("<span>%s</span> %s", $realprice, $curname);

				if($item["sale_type"]=="Продажа" && (int)$ploshad_explode > 0){
					$m2price = round($realprice / (int)$ploshad_explode);
					$rsl .= sprintf("<br /><span style=\"font-size:12px;\">%s %s / м<sup>2</span>", $m2price, $curname);
				}

				$rsl .= "</section>";
				$rsl .= sprintf("<section class=\"col-sm-4 g-image-container g-container%d\" data-loading=\"false\">", $item["idx"]);

				$rsl .= sprintf(
					"<div class=\"g-infox\"><span class=\"g-activex\">1</span> - <span class=\"g-maxx\">%s</span></div>",
					$item["photoCount"]
                );

				$rsl .= sprintf(
					"<div class=\"g-leftar g-arrow\" data-idx=\"%s\" data-active=\"1\" data-max=\"%d\"><i class=\"fa fa-angle-left\"></i></div>",
					$item["idx"], 
					$item["photoCount"]
				);

				$rsl .= sprintf(
					"<a href=\"/%s/view/%s/?id=%s\" class=\"g-image-sliderx%s\">",
					$_SESSION["LANG"], 
					urlencode(strip_tags($item["title"])), 
					(int)$item["idx"],
					$pn
				);
				$rsl .= sprintf(
				"<img src=\"%s/image/loadimage?f=%s&w=%s&h=%s\" class=\"loading img-responsive img-thumbnail\" />", 
				Config::WEBSITE.$_SESSION["LANG"], 
				base64_encode(Config::WEBSITE_.$item["photo"]), 
				base64_encode("220"), 
				base64_encode("220")
				);
				$rsl .= "</a>";

				$rsl .= sprintf(
					"<div class=\"g-rightar g-arrow\" data-idx=\"%d\" data-active=\"1\" data-max=\"%d\"><i class=\"fa fa-angle-right\"></i></div>",
					$item["idx"],
					$item["photoCount"]
				);

				$rsl .= "</section>";

				$rsl .= "<section class=\"col-sm-8\">";


				$rsl .= sprintf(
					"<a href=\"/%s/view/%s/?id=%s\" style=\"color:white; text-decoration:none\">",
					$_SESSION["LANG"], 
					urlencode(strip_tags($item["title"])), 
					(int)$item["idx"]
				);
				$rsl .= "<section class=\"flat-labels\">";
				// $rsl .= sprintf("<span>%s</span>", $item["sale_type"]); 
				$rsl .= sprintf("<span title=\"%s\">%s</span>", htmlentities($item["type"]), $string::cutstatic($item["type"], 8));
				$rsl .= sprintf("<span>%s</span>", $item["rooms"]);
				$rsl .= sprintf("<span>%s Спальни</span>", (int)@$spalni_explode);			
				
				$rsl .= sprintf("<span>%s м<sup>2</sup></span>", (int)@$ploshad_explode);
				$rsl .= sprintf("<span>%s/%s зтаж</span>",(int)@$etaj_explode, (int)@$totaletaj_explode);

				$rsl .= "</section>";
				$rsl .= "<h4 style=\"text-align: left; line-height: 22px;\" class=\"g-desktop-width400 g-margin-top-desktop40\">";
				$rsl .= sprintf("<span>%s</span>", $item["title"]);
				$rsl .= "<span class=\"text-primary\" style=\"margin: 10px 0 0 5px;\">";
				$rsl .= "<i class=\"fa fa-hand-o-right\"></i>";
				$rsl .= sprintf("&nbsp;ID <span>%s</span>", $item["orderid"]);
				$rsl .= "</span>";
				$rsl .= "</h4>";
				$rsl .= "<section class=\"text-muted margin-top20\">";
				// $rsl .= $string->cut(strip_tags($item["description"]), 120);

				// $e = explode(",", $item["statistic"]);
				$rsl .= sprintf(
					"<p style=\"margin:10px 0 0 0;\"><strong>Адрес: </strong>%s</p>",
					$item["address"]
				);

				$rsl .= "<p>&nbsp;</p>";

				$db_views = new Database("products", array(
                  "method"=>"select_views",
                  "idx"=>$item["idx"]
                ));
                $views = $db_views->getter();

				// $rsl .= sprintf(
				// 	"<strong>Просмотров всево: %s Cегодня: %s <em onclick=\"location.href='/ru/searchonmap/?idx=%s'; return false;\">Показать на карте</em></strong>",
				// 	$views["totalviews"],
				// 	$views["todayviews"],
				// 	$item["idx"]
				// );

				$rsl .= sprintf(
					"<strong>Просмотров всево: %s Cегодня: %s</strong>",
					$views["totalviews"],
					$views["todayviews"]
				);
				// Сегодния: %s
				$rsl .= "</a>";
				$rsl .= "</section>";
				
				$rsl .= "</section>";
				$rsl .= "</section>";
				$rsl .= "</div>";
				endforeach;
				
				$this->out = array(
					"Error" => array(
						"Code"=>0, 
						"Text"=>"",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>1, 
						"Text"=>"Успех",
						"results"=>$rsl,
						"loaded"=>$nextFrom,
						"Details"=>""
					)
				);
			}else{
				$this->out = array(
					"Error" => array(
						"Code"=>1, 
						"Text"=>"<p class='list-group-item' style='color: white; font-size:22px;'>Нет данных</p>",
						"Details"=>""
					),
					"Success"=>array(
						"Code"=>0, 
						"Text"=>"",
						"results"=>"",
						"loaded"=>0,
						"Details"=>""
					)
				);
			}
			
		}

		return $this->out;
	}
}