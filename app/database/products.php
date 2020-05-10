<?php 
class products
{
	private $conn;

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if(method_exists("products", $args['method']))
		{
			$out = $this->$args['method']($args);
		}
		return $out;
	}

	private function select_views($args)
	{
		$totalviews = 0;
		$todayviews = 0;
		$select = "SELECT `views` FROM `products` WHERE `idx`=:idx AND `lang`=:lang AND `visibility`=0 AND `status`!=1";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":idx"=>$args['idx'],
			":lang"=>$_SESSION["LANG"]
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
			$totalviews = $fetch["views"];
		}

		$select2 = "SELECT `views` FROM `product_views` WHERE `pid`=:idx AND `date`=:datex";
		$prepare2 = $this->conn->prepare($select2); 
		$prepare2->execute(array(
			":idx"=>$args['idx'],
			":datex"=>date("Y-m-d")
		));
		if($prepare2->rowCount()){
			$fetch2 = $prepare2->fetch(PDO::FETCH_ASSOC);
			$todayviews = $fetch2["views"];
		}

		$out["totalviews"] = $totalviews;
		$out["todayviews"] = $todayviews;

		return $out;
	}

	private function remove_views($args)
	{
		$remove = "DELETE FROM `product_views` WHERE date < '2018-06-08'";
		$prepare = $this->conn->query($remove);
		return true;
	}

	private function update_prices($args)
	{
		$selectCur = "SELECT `name`,`value` FROM `currency` WHERE `name` IN('USD','RUB')";
		$prepareCur = $this->conn->prepare($selectCur);
		$prepareCur->execute();

		$valuta = array();
		if($prepareCur->rowCount())
		{
			$fetchCur = $prepareCur->fetchAll(PDO::FETCH_ASSOC);
			foreach ($fetchCur as $v):
				$valuta[$v["name"]] = $v["value"];
			endforeach;
		}


		$selectAll = "SELECT `idx`, `price` FROM `products` WHERE `status`!=1";
		$prepareAll = $this->conn->prepare($selectAll);
		$prepareAll->execute();

		if($prepareAll->rowCount() && isset($valuta["USD"]) && isset($valuta["RUB"]))
		{

			$fetch = $prepareAll->fetchAll(PDO::FETCH_ASSOC); 
			foreach($fetch as $val):
				$price_gel = (int)((float)$val["price"] * (float)$valuta["USD"]);
				$price_usd = (int)$val["price"];
				$price_rub = (int)( $price_gel / (float)((float)$valuta["RUB"] / 100) );

				// echo $price_usd." ".$price_rub."<br />";

				$update = "UPDATE `products` SET `price_gel`=:price_gel, `price_usd`=:price_usd, `price_rub`=:price_rub WHERE `idx`=:idx";
				$prepare = $this->conn->prepare($update); 
				$prepare->execute(array(
					":price_gel"=>$price_gel,
					":price_usd"=>$price_usd,
					":price_rub"=>$price_rub,
					":idx"=>$val["idx"]
				));
			endforeach;
		}

		$this->clearCache();
		
	}

	private function update_views($args)
	{
		$this->remove_views(array()); 
		
		$update = "UPDATE `products` SET `views`=`views`+1 WHERE `idx`=:idx AND `visibility`=0 AND `status`!=1";
		$prepare = $this->conn->prepare($update); 
		$prepare->execute(array(
			":idx"=>$args['idx']
		));

		$select2 = "SELECT `views` FROM `product_views` WHERE `pid`=:idx AND `date`=:datex";
		$prepare2 = $this->conn->prepare($select2); 
		$prepare2->execute(array(
			":idx"=>$args['idx'],
			":datex"=>date("Y-m-d")
		));
		if($prepare2->rowCount()){
			$fetch2 = $prepare2->fetch(PDO::FETCH_ASSOC);
			
			$update = "UPDATE `product_views` SET `views`=`views`+1 WHERE `pid`=:idx AND `date`=:datex";
			$prepare3 = $this->conn->prepare($update); 
			$prepare3->execute(array(
				":idx"=>$args['idx'],
				":datex"=>date("Y-m-d")
			));
		}else{
			$insert = "INSERT INTO `product_views` SET `views`=1, `pid`=:idx, `date`=:datex";
			$prepare3 = $this->conn->prepare($insert); 
			$prepare3->execute(array(
				":idx"=>$args['idx'],
				":datex"=>date("Y-m-d")
			));
		}

		return true;
	}

	private function select_website($args)
	{
		require_once("app/functions/request.php"); 

		$sale_type = '';
		$cities = '';
		$rooms = '';
		$type = '';
		$price = '';
		$forderby = "CAST(`products`.`orderid` as SIGNED) ASC";
		$forderby_json = "SIGNED_ASC";
		$sale_type_json = '';
		$cities_json = '';
		$rooms_json = '';
		$type_json = '';
		$price_json = '';

		if(isset($args["sale_type"]) && is_numeric($args["sale_type"])){
			$sale_type = ' AND `products`.`sale_type`='.$args["sale_type"];
			$sale_type_json = 'sale'.$args["sale_type"];
		}

		if(isset($args["cities"]) && is_numeric($args["cities"])){
			$cities = ' AND `products`.`cities`='.$args["cities"];
			$cities_json = 'cities'.$args["cities"];
		}
		
		if(isset($args["rooms"]) && is_numeric($args["rooms"])){
			$rooms = ' AND `products`.`rooms`='.$args["rooms"];
			$rooms_json = 'room'.$args["rooms"];
		}
		if(isset($args["type"]) && is_numeric($args["type"])){
			$type = ' AND `products`.`type`='.$args["type"];
			$type_json = 'type'.$args["type"];
		}

		if(
			isset($args["price_from"]) && 
			is_numeric($args["price_from"]) && 
			isset($args["price_to"]) && 
			is_numeric($args["price_to"]) && 
			$args["price_from"]>0 &&
			$args["price_to"]>0
		){
			$price = ' AND `products`.`price`>='.$args["price_from"].' AND `products`.`price`<='.$args["price_to"];
			$price_json = 'priceboth'.$args["price_from"].$args["price_to"];
		}else if(
			isset($args["price_from"]) && 
			is_numeric($args["price_from"]) &&  
			$args["price_from"]>0  
		){
			$price = ' AND `products`.`price`>='.$args["price_from"];
			$price_json = 'pricefrom'.$args["price_from"];
		}else if(
			isset($args["price_to"]) && 
			is_numeric($args["price_to"]) && 
			$args["price_to"]>0
		){
			$price = ' AND `products`.`price`<='.$args["price_to"];
			$price_json = 'priceto'.$args["price_to"];
		}

		if(isset($args["forderby"]) && $args["forderby"]=="desc"){
			$forderby = "CAST(`products`.`price` as SIGNED) DESC";
			$forderby_json = "SIGNED_DESC";
		}

		if(isset($args["forderby"]) && $args["forderby"]=="asc"){
			$forderby = "CAST(`products`.`price` as SIGNED) ASC";
			$forderby_json = "SIGNED_ASC";
		}

		$fetch = "[]";
		$itemPerPage = $args['itemPerPage'];
		if(isset($args["pn"])){ $_GET["pn"] = $args["pn"]; }

		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (((int)$_GET['pn']-1)*$itemPerPage) : 0;
		// echo $from;
		
		$json = Config::CACHE."productsweb_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$itemPerPage.$from.$sale_type_json.$cities_json.$rooms_json.$type_json.$price_json.$forderby_json.".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			
			$select = "SELECT 
			(SELECT COUNT(`id`) FROM `products` WHERE `products`.`pid`=:pid AND `products`.`lang`=:lang AND `products`.`status`!=:one) as counted, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`sale_type`) AS sale_type, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`cities`) AS cities, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`rooms`) AS rooms, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`type`) AS type, 
			`products`.`idx`, 
			`products`.`orderid`, 
			`products`.`title`,
			`products`.`price`,
			`products`.`statistic`,
			`products`.`description`,
			`products`.`additional_data`,
			`products`.`address`,
			`products`.`location`,
			`products`.`views`,
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo,
			(SELECT COUNT(`photos`.`id`) FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one) AS photoCount
			FROM 
			`products` 
			WHERE 
			`products`.`pid`=:pid AND 
			`products`.`lang`=:lang AND 
			`products`.`showwebsite`=2 AND 
			`products`.`status`!=:one ".$sale_type.$cities.$rooms.$type.$price."
			ORDER BY ".$forderby." LIMIT ".$from.",".$itemPerPage;

			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":pid"=>2, 
				":lang"=>$args['lang'],
				":one"=>1
			));
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$fetch = @file_get_contents($json);
			}
		}
		return json_decode($fetch, true);
	}

	private function select_website_map($args)
	{
		require_once("app/functions/request.php"); 

		$sale_type = '';
		$rooms = '';
		$type = '';
		$price = '';
		$forderby = "`products`.`orderid` ASC";
		$sale_type_json = '';
		$rooms_json = '';
		$type_json = '';
		$price_json = '';

		if(isset($args["sale_type"]) && is_numeric($args["sale_type"])){
			$sale_type = ' AND `products`.`sale_type`='.$args["sale_type"];
			$sale_type_json = 'sale'.$args["sale_type"];
		}
		if(isset($args["rooms"]) && is_numeric($args["rooms"])){
			$rooms = ' AND `products`.`rooms`='.$args["rooms"];
			$rooms_json = 'room'.$args["rooms"];
		}
		if(isset($args["type"]) && is_numeric($args["type"])){
			$type = ' AND `products`.`type`='.$args["type"];
			$type_json = 'type'.$args["type"];
		}

		if(
			isset($args["price_from"]) && 
			is_numeric($args["price_from"]) && 
			isset($args["price_to"]) && 
			is_numeric($args["price_to"]) && 
			$args["price_from"]>0 &&
			$args["price_to"]>0 
		){
			$price = ' AND `products`.`price`>='.$args["price_from"].' AND `products`.`price`<='.$args["price_to"];
			$price_json = 'priceboth'.$args["price_from"].$args["price_to"];
		}else if(
			isset($args["price_from"]) && 
			is_numeric($args["price_from"]) && 
			$args["price_from"]>0 
		){
			$price = ' AND `products`.`price`>='.$args["price_from"];
			$price_json = 'pricefrom'.$args["price_from"];
		}else if(
			isset($args["price_to"]) && 
			is_numeric($args["price_to"]) && 
			$args["price_to"]>0
		){
			$price = ' AND `products`.`price`<='.$args["price_to"];
			$price_json = 'priceto'.$args["price_to"];
		}

		$fetch = "[]";
		$idx = "";
		$idxsql = "";
		if(functions\request::index("GET", "idx")){
			$idx = functions\request::index("GET", "idx");
			$idxsql = " AND `idx`=".$idx;
			$sale_type = "";
			$rooms = "";
			$type="";
			$price="";
		}

		$json = Config::CACHE."productsweb_map_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$sale_type_json.$rooms_json.$type_json.$price_json.$idx.".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			
			$select = "SELECT 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`sale_type`) AS sale_type, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`rooms`) AS rooms, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`type`) AS type, 
			`products`.`idx`, 
			`products`.`orderid`, 
			`products`.`title`,
			`products`.`price`,
			`products`.`statistic`,
			`products`.`description`,
			`products`.`additional_data`,
			`products`.`location`,
			`products`.`address`,
			`products`.`views`,
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo,
			(SELECT COUNT(`photos`.`id`) FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one) AS photoCount
			FROM 
			`products` 
			WHERE 
			`products`.`pid`=:pid AND 
			`products`.`lang`=:lang AND 
			`products`.`showwebsite`=2 AND 
			`products`.`status`!=:one ".$sale_type.$rooms.$type.$price.$idxsql;

			// echo $select;

			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":pid"=>2, 
				":lang"=>$args['lang'],
				":one"=>1
			));
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$fetch = @file_get_contents($json);
			}
		}
		return json_decode($fetch, true);
	}

	private function select($args)
	{
		require_once("app/functions/request.php"); 
		$fetch = "[]";
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (((int)$_GET['pn']-1)*$itemPerPage) : 0;
		$s = (isset($args["s"]) && $args["s"]>0) ? ' `products`.`orderid`='.$args["s"].' AND ' : '';
		$sjson = (isset($args["s"]) && $args["s"]>0) ? $args["s"].'x' : '';

		if(isset($args['showwebsite'])){ $show="2"; }
		else{  $show="1"; }
		$json = Config::CACHE."products_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$show.$itemPerPage.$from.$sjson.".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			$showwebsiteSql = "";
			if(isset($args['showwebsite'])){
				$showwebsiteSql = ' `products`.`showwebsite`=:showwebsite AND ';
			}
			$select = "SELECT 
			(SELECT COUNT(`id`) FROM `products` WHERE `products`.`pid`=:pid AND `products`.`lang`=:lang AND" . $showwebsiteSql ."`products`.`status`!=:one) as counted, 
			`products`.`idx`, 
			`products`.`orderid`, 
			`products`.`title`,
			`products`.`price`,
			`products`.`short_description`, 
			`products`.`description`,
			`products`.`showwebsite`,
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
			FROM 
			`products` 
			WHERE 
			`products`.`pid`=:pid AND".$s."
			`products`.`lang`=:lang AND " . $showwebsiteSql ."
			`products`.`status`!=:one 
			ORDER BY `products`.`id` DESC LIMIT ".$from.",".$itemPerPage;


			$prepare = $this->conn->prepare($select); 
			if(isset($args['showwebsite'])){
				$prepare->execute(array(
					":pid"=>2, 
					":lang"=>$args['lang'],
					":showwebsite"=>$args['showwebsite'],
					":one"=>1
				));
			}else{
				$prepare->execute(array(
					":pid"=>2, 
					":lang"=>$args['lang'],
					":one"=>1
				));
			}
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$fetch = @file_get_contents($json);
			}
		}
		return json_decode($fetch, true);
	}

	private function usersProducts($args)
	{
		$db_fetch = array();
		$select = "SELECT 
		(SELECT COUNT(`id`) FROM `products` WHERE `products`.`pid`=:pid AND `products`.`lang`=:lang AND `products`.`status`!=:one) as counted, 
		(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`sale_type`) AS sale_type, 
		(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`rooms`) AS rooms, 
		(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`type`) AS type, 
		`products`.`idx`, 
		`products`.`orderid`, 
		`products`.`title`,
		`products`.`price`,
		`products`.`description`,
		`products`.`showwebsite`,
		(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
		FROM 
		`users_website`, `products` 
		WHERE 
		`users_website`.`email`=:insert_admin AND 
		`users_website`.`id`=`products`.`insert_admin` AND 
		`products`.`pid`=:pid AND 
		`products`.`lang`=:lang AND
		`products`.`status`!=:one 
		ORDER BY `products`.`orderid` ASC";

		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":pid"=>2, 
			":lang"=>$args['lang'],
			":insert_admin"=>$_SESSION["bemyguest_user"],
			":one"=>1
		));
		if($prepare->rowCount()){
			$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}

		return $db_fetch;
	}

	private function add($args)
	{
		$current_lang = $args["lang"];
		$catalogId = (int)$args["catalogId"];
		$date = strtotime($args['date']);
		$orderid = $args["orderid"];
		$title = $args["title"];		
		$sale_type = $args["sale_type"];
		$cities = $args["cities"];
		$rooms = $args["rooms"];
		$type = $args["type"];
		$price = $args["price"];
		$pricebymonth = implode(",",$args["pricebymonth"]);
		$additional_data = implode(",",$args["additional_data"]);
		$short_description = $args["shortDescription"];
		$description = $args["longDescription"];
		$address = $args["address"];
		$location = $args["locations"];
		$showwebsite = $args["showwebsite"];
		$insert_admin = isset($args["insert_admin"]) ? $args["insert_admin"] : 14;
		
		$select = "SELECT `title` FROM `languages`";
		$prepare = $this->conn->prepare($select);
		$prepare->execute();
		$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

		$max = "SELECT MAX(`idx`) as maxidx FROM `products`";
		$prepare2 = $this->conn->prepare($max);
		$prepare2->execute();
		$fetch2 = $prepare2->fetch(PDO::FETCH_ASSOC);
		$maxId = ($fetch2["maxidx"]) ? $fetch2["maxidx"] + 1 : 1;
	

		foreach ($fetch as $val) {
			$insert = "INSERT INTO `products` SET 
			`idx`=:idx, 
			`pid`=:pid, 
			`date`=:datex, 
			`orderid`=:orderid, 
			`title`=:title, 
			`sale_type`=:sale_type, 
			`cities`=:cities, 
			`rooms`=:rooms, 
			`type`=:type, 
			`price`=:price, 
			`pricebymonth`=:pricebymonth, 
			`short_description`=:short_description, 
			`description`=:description, 
			`location`=:location, 
			`address`=:address,
			`showwebsite`=:showwebsite,
			`insert_admin`=:insert_admin,
			`additional_data`=:additional_data,
			`visibility`=:visibility,  
			`views`=:views,  
			`status`=:status, 
			`lang`=:lang";
			$prepare3 = $this->conn->prepare($insert);
			$prepare3->execute(array(
				":idx"=>$maxId, 
				":pid"=>$catalogId, 
				":datex"=>$date, 
				":orderid"=>$orderid, 
				":title"=>$title, 
				":sale_type"=>$sale_type, 
				":cities"=>$cities, 
				":rooms"=>$rooms, 
				":type"=>$type, 
				":price"=>$price,
				":pricebymonth"=>$pricebymonth,
				":short_description"=>$short_description,
				":description"=>$description,
				":address"=>$address,
				":location"=>$location,
				":showwebsite"=>$showwebsite,
				":insert_admin"=>$insert_admin,
				":additional_data"=>$additional_data,
				":views"=>"0",
				":status"=>"0",
				":visibility"=>0,
				":lang"=>$val['title']
			)); 

			if(count($args["serialPhotos"])){
				foreach ($args["serialPhotos"] as $pic) {
					if(!empty($pic)):
					$photo = 'INSERT INTO `photos` SET `parent`=:parent, `path`=:pathx, `type`=:type, `lang`=:lang, `size`=:size, `status`=:zero';
					$photoPerpare = $this->conn->prepare($photo);
					$photoPerpare->execute(array(
						":parent"=>$maxId, 
						":pathx"=>$pic, 
						":type"=>"products", 
						":size"=>"0", 
						":lang"=>$val['title'], 
						":zero"=>0
					));
					endif;
				}
			}
		}

		$this->clearCache();
	}

	private function editProductByUser($args)
	{
		$current_lang = $args["lang"];
		$orderid = $args["orderid"];
		$title = $args["title"];		
		$sale_type = $args["sale_type"];
		$rooms = $args["rooms"];
		$type = $args["type"];
		$price = $args["price"];
		$pricebymonth = implode(",",$args["pricebymonth"]);
		$additional_data = implode(",",$args["additional_data"]);
		$short_description = $args["shortDescription"];
		$description = $args["longDescription"];
		$address = $args["address"];
		$location = $args["locations"];
		$showwebsite = $args["showwebsite"];
		$insert_admin = isset($args["insert_admin"]) ? $args["insert_admin"] : 14;
		$idx = (int)functions\request::index("GET","id");
	
		
		$insert = "UPDATE `products` SET 
		`orderid`=:orderid, 
		`title`=:title, 
		`sale_type`=:sale_type, 
		`rooms`=:rooms, 
		`type`=:type, 
		`price`=:price, 
		`pricebymonth`=:pricebymonth, 
		`short_description`=:short_description, 
		`description`=:description, 
		`address`=:address,
		`location`=:location, 			
		`showwebsite`=:showwebsite,
		`additional_data`=:additional_data,
		`visibility`=:visibility,  
		`views`=:views,  
		`status`=:status
		WHERE 
		`insert_admin`=:insert_admin AND 
		`lang`=:lang AND 
		`idx`=:idx";
		$prepare3 = $this->conn->prepare($insert);
		$prepare3->execute(array(
			":orderid"=>$orderid, 
			":title"=>$title, 
			":sale_type"=>$sale_type, 
			":rooms"=>$rooms, 
			":type"=>$type, 
			":price"=>$price,
			":pricebymonth"=>$pricebymonth,
			":short_description"=>$short_description,
			":description"=>$description,
			":address"=>$address,
			":location"=>$location,
			":showwebsite"=>$showwebsite,
			":additional_data"=>$additional_data,
			":views"=>"0",
			":status"=>"0",
			":visibility"=>"0",
			":insert_admin"=>$insert_admin,
			":lang"=>$current_lang,
			":idx"=>$idx
		)); 


		if(count($args["serialPhotos"])){
			$select = "SELECT `title` FROM `languages`";
			$prepare = $this->conn->prepare($select);
			$prepare->execute();
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);			

			foreach ($fetch as $val) {	
				foreach ($args["serialPhotos"] as $pic) {
					if(!empty($pic)):
					$photo = 'INSERT INTO `photos` SET `parent`=:parent, `path`=:pathx, `type`=:type, `lang`=:lang, `size`=:size, `status`=:zero';
					$photoPerpare = $this->conn->prepare($photo);
					$photoPerpare->execute(array(
						":parent"=>$idx, 
						":pathx"=>$pic, 
						":type"=>"products", 
						":size"=>"0", 
						":lang"=>$val['title'], 
						":zero"=>0
					));
					endif;
				}
			}

		}

		$this->clearCache();
	}

	private function remove($args)
	{
		$val = $args['val'];
		$update = "UPDATE `products` SET `status`=:one WHERE `idx`=:idx";
		$prepare = $this->conn->prepare($update); 
		$prepare->execute(array(
			":one"=>1,
			":idx"=>$val
		));
		if($prepare->rowCount()){
			$update2 = "UPDATE `photos` SET `status`=:one WHERE `parent`=:parent AND `type`=:type";
			$prepare2 = $this->conn->prepare($update2); 
			$prepare2->execute(array(
				":one"=>1,
				":parent"=>$val,
				":type"=>"products"
			));

			$delete = "DELETE FROM `subservices` WHERE `product_idx`=:product_idx";
			$prepare3 = $this->conn->prepare($delete); 
			$prepare3->execute(array(
				":product_idx"=>$val
			));
		}
		$this->clearCache();
		return 1;
	}

	private function remove_from_user($args)
	{
		$val = $args['val'];
		$update = "UPDATE `products` SET `status`=:one WHERE `idx`=:idx AND `insert_admin`=:insert_admin";
		$prepare = $this->conn->prepare($update); 
		$prepare->execute(array(
			":one"=>1,
			":idx"=>$val,
			":insert_admin"=>$args['user']
		));
		if($prepare->rowCount()){
			$update2 = "UPDATE `photos` SET `status`=:one WHERE `parent`=:parent AND `type`=:type";
			$prepare2 = $this->conn->prepare($update2); 
			$prepare2->execute(array(
				":one"=>1,
				":parent"=>$val,
				":type"=>"products"
			));

			$delete = "DELETE FROM `subservices` WHERE `product_idx`=:product_idx";
			$prepare3 = $this->conn->prepare($delete); 
			$prepare3->execute(array(
				":product_idx"=>$val
			));
		}
		$this->clearCache();
		return 1;
	}

	private function selectFlatsForCron($args)
	{
		$fetch = array();
		$select = "SELECT * FROM `products` 
		WHERE 
		(`sale_type`=54 OR `sale_type`=55) AND 
		`showwebsite`=2 AND 
		`status`!=1 
		ORDER BY `id` DESC";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute();
		
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}

		return $fetch;
	}

	private function updateFlatPricesFromCron($args)
	{
		$update = "UPDATE `products` 
		SET 
		`price`=:price 
		WHERE 
		`idx`=:idx";
		$prepare = $this->conn->prepare($update); 
		$prepare->execute(array(
			":price"=>$args["price"],
			":idx"=>$args["idx"]
		));

		return 1;
	}

	private function selectById($args)
	{
		$fetch = "";
		if(isset($args['showwebsite'])){ $show="2"; }
		else{  $show="1"; }


		$json = Config::CACHE."products_byid_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$show.$args['idx'].".json";
		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{	
			$showwebsiteSql = "";
			if(isset($args['showwebsite'])){
				$showwebsiteSql = ' `showwebsite`=:showwebsite AND ';
			}

			$insert_adminSql = "";
			if(isset($args['insert_admin'])){
				$insert_adminSql = ' `insert_admin`="'.$args['insert_admin'].'" AND ';
			}

			$select = "SELECT 
			(SELECT `users_website`.`email` FROM `users_website` WHERE `products`.`insert_admin`=`users_website`.`id`) AS admin_email, 
			(SELECT `users_website`.`firstname` FROM `users_website` WHERE `products`.`insert_admin`=`users_website`.`id`) AS admin_firstname, 
			(SELECT `users_website`.`phone` FROM `users_website` WHERE `products`.`insert_admin`=`users_website`.`id`) AS admin_phone, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`sale_type`) AS sale_type_title, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`rooms`) AS rooms_title, 
			(SELECT `usefull`.`title` FROM `usefull` WHERE `usefull`.`idx`=`products`.`type`) AS type_title, 
			`products`.* 
			FROM `products` WHERE `idx`=:idx AND `lang`=:lang AND ".$showwebsiteSql.$insert_adminSql."`status`!=:one";
			$prepare = $this->conn->prepare($select); 
			if(isset($args['showwebsite'])){
				$prepare->execute(array(
					":idx"=>$args['idx'], 
					":lang"=>$args['lang'], 
					":showwebsite"=>$args['showwebsite'], 
					":one"=>1
				));
			}else{
				$prepare->execute(array(
					":idx"=>$args['idx'], 
					":lang"=>$args['lang'], 
					":one"=>1
				));
			}
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetch(PDO::FETCH_ASSOC);
				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);
				$fetch = @file_get_contents($json);
			}

		}
		$return = json_decode($fetch, true);

		if(isset($args["increament"])){
			$statistic = $return["statistic"];

			if(preg_match_all("/view\:(\d+)\,(\d{1,2}\/\d{1,2}\/\d{4})\:(\d+)/", $statistic, $matches)){
				$all = (isset($matches[1][0])) ? (int)$matches[1][0] : 0;
				$date = (isset($matches[2][0])) ? $matches[2][0] : "";
				$today = (isset($matches[3][0])) ? (int)$matches[3][0] : 0;

				if($date==date("d/m/Y"))
				{
					$today = $today + 1;
				}else{
					$today = 1;
				}
				$newString = sprintf(
					"view:%d,%s:%d",
					($all+1),
					date("d/m/Y"),
					$today
				);

				// echo $newString;

				$increament = "UPDATE `products` SET `statistic`=:statistic WHERE `idx`=:idx";
				$in_prepare = $this->conn->prepare($increament); 
				$in_prepare->execute(array(
					":statistic"=>$newString, 
					":idx"=>$args['idx']
				));
			}
			$this->clearCache();
		}
		

		return $return;
	}

	private function edit($args)
	{
		$current_lang = $args["lang"];
		$idx = (int)$args["idx"];
		$date = strtotime($args['date']);
		$orderid = $args["orderid"];
		$title = $args["title"];		
		$sale_type = $args["sale_type"];
		$cities = $args["cities"];
		$rooms = $args["rooms"];
		$type = $args["type"];
		$price = $args["price"];
		$pricebymonth = implode(",",$args["pricebymonth"]);
		$additional_data = implode(",",$args["additional_data"]);
		$short_description = $args["shortDescription"];
		$description = $args["longDescription"];
		$address = $args["address"];
		$location = $args["locations"];
		$showwebsite = $args["showwebsite"];

		// update only sing language
		$update = "UPDATE `products` SET 
		`title`=:title, 
		`short_description`=:short_description, 
		`description`=:description,
		`address`=:address
		WHERE `idx`=:idx AND `lang`=:lang";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":title"=>$title, 
			":short_description"=>$short_description,  
			":description"=>$description,  
			":address"=>$address,  
			":idx"=>$args['idx'],  
			":lang"=>$args['lang']   
		));
		
		// update in all language
		$updateShow = "UPDATE `products` SET 
		`date`=:datex, 
		`orderid`=:orderid, 
		`sale_type`=:sale_type, 
		`cities`=:cities, 
		`rooms`=:rooms, 
		`type`=:type, 
		`price`=:price, 
		`pricebymonth`=:pricebymonth, 
		`additional_data`=:additional_data,
		`location`=:location,
		`showwebsite`=:showwebsite
		WHERE `idx`=:idx";
		$prepareShow = $this->conn->prepare($updateShow);
		$prepareShow->execute(array(
			":datex"=>$date, 
			":orderid"=>$orderid,
			":sale_type"=>$sale_type,
			":cities"=>$cities,
			":rooms"=>$rooms, 
			":type"=>$type, 
			":price"=>$price, 
			":pricebymonth"=>$pricebymonth,
			":additional_data"=>$additional_data, 
			":location"=>$location, 
			":showwebsite"=>$showwebsite, 
			":idx"=>$args['idx']
		));


		$photos = new Database('photos', array(
			'method'=>'deleteByParent', 
			'idx'=>$args['idx'], 
			'type'=>"products",
			'lang'=>$args['lang'] 
		));

		if(count($args["serialPhotos"])){

			foreach($args["serialPhotos"] as $pic) {
				if(!empty($pic)):
				$photo = 'INSERT INTO `photos` SET `parent`=:parent, `path`=:pathx, `type`=:type, `lang`=:lang, `status`=:zero';
				$photoPerpare = $this->conn->prepare($photo);
				$photoPerpare->execute(array(
					":parent"=>$args['idx'], 
					":pathx"=>$pic, 
					":type"=>"products", 
					":lang"=>$args['lang'], 
					":zero"=>0
				));
				endif;
			}
		}

		$this->clearCache();
	}

	public function countRegions($args){
		$count = 0;
		$sql = "SELECT DISTINCT `region` FROM `products` WHERE `lang`='ge' AND `status`!=1"; 
		$prepare = $this->conn->prepare($sql); 
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
			$count = count($fetch);
		}
		return $count;
	}

	private function countByDestination($args)
	{
		$count = 0;
		$select = "SELECT COUNT(`id`) as counted FROM `products` WHERE FIND_IN_SET(:numberx, `destination`) AND `showwebsite`=2 AND `lang`=:lang AND `status`!=1";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":numberx"=>$args["numberx"],
			":lang"=>$args["lang"],
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
			$count = $fetch["counted"];
		}
		return $count;
	}

	private function clearCache($args = false)
	{ 
		$mask = Config::CACHE.'products_*.*';
		array_map('unlink', glob($mask));

		$mask1 = Config::CACHE.'productsweb_*.*';
		array_map('unlink', glob($mask1));

		$mask2 = Config::CACHE.'subservicves_*.*';
		array_map('unlink', glob($mask2));

		$mask3 = Config::CACHE.'module_*.*';
		array_map('unlink', glob($mask3));	
	}
}