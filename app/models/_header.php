<?php 
class _header
{
	public $public;
	public $lang;
	public $pagedata;
	public $imageSrc;
	public $product;

	public function index(){ 

		$getter = $this->pagedata->getter(); 
		$title = "Главная";
		$description = "";
		$image = "";

		if(isset($getter['title'])){
			$title = strip_tags($getter['title']);
			$description = strip_tags($getter['description']);
			$image = Config::WEBSITE_.strip_tags($getter['photo']);
		}else if(isset($getter[0]['title'])){
			$title = strip_tags($getter[0]['title']); 
			$description = strip_tags($getter[0]['description']);
			$image = Config::WEBSITE_.strip_tags($getter[0]['photo']);
		}

		if(isset($this->product)){
			$title = strip_tags($this->product['title']);
			$description = strip_tags($this->product['short_description']);

			$db_photos = new Database("photos", array(
				"method"=>"selectByParent", 
				"idx"=>$this->product["idx"],
				"type"=>"products",
				"lang"=>$_SESSION["LANG"]
			));
			$getter = $db_photos->getter();
			$image = sprintf(
				"%s%s/image2/loadimage?f=%s&w=%s&h=%s&reset=1",
				Config::WEBSITE, 
                $_SESSION["LANG"],
                base64_encode(Config::WEBSITE_.$getter[0]["path"]), 
                base64_encode(728),
                base64_encode(546),
                "empty",
                728,
                546
			);

			// $image = "http://flatsinbatumi.com/".file_get_contents($image);
			// echo $image;
			// exit;

			// $image = Config::WEBSITE_.$getter[0]["path"];
		}

		$out = "<!DOCTYPE html>\n";
		$out .= "<html>\n";
		$out .= "<head>\n";
		$out .= "<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-149836302-1\"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-149836302-1');
		</script>";
		
		$out .= "<meta charset=\"utf-8\">\n";
		$out .= "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
				
		$out .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />\n";
		$out .= "<meta name=\"format-detection\" content=\"telephone=no\"/>\n";
		$out .= sprintf("<title>%s - %s</title>\n", strip_tags($title), Config::NAME);
		
		$actual_link = "http://".$_SERVER["HTTP_HOST"].htmlentities($_SERVER["REQUEST_URI"]);
		$out .= "<meta property=\"fb:app_id\" content=\"1610219615727288\" />\n";
		$out .= "<meta property=\"og:title\" content=\"".strip_tags($title)."\" />\n";
		$out .= "<meta property=\"og:type\" content=\"website\" />\n";
		$out .= "<meta property=\"og:url\" content=\"".$actual_link."\"/>\n";
		
		// if(isset($this->imageSrc)){
		// 	$image = $this->imageSrc;
		// }else{
		// 	$image = $this->public."img/share2.jpg?v=2";
		// }
		$out .= sprintf(
			"<meta property=\"og:image\" content=\"%s\" />\n", 
			$image
		);
		$out .= sprintf(
			"<link rel=\"image_src\" type=\"image/jpeg\" href=\"%s\" />\n", 
			$image
		);

		$out .= "<script type=\"application/ld+json\">";
		$out .= '{
			"@context": "https://schema.org",
			"@type": "Product",
			"name": "'.htmlentities(strip_tags($title)).'",
			"image": "'.$image.'",
			"description":"'.htmlentities(strip_tags($description)).'"
		}';
		$out .= "</script>";

		$out .= "<meta property=\"og:image:width\" content=\"1200\" />\n";
		$out .= "<meta property=\"og:image:height\" content=\"630\" />\n";
		$out .= "<meta property=\"og:image:type\" content=\"image/jpeg\">\n";
		$out .= "<meta property=\"og:site_name\" content=\"КВАРТИРЫ В БАТУМИ\" />\n";
		$out .= "<meta property=\"og:description\" content=\"".htmlentities(strip_tags($description))."\"/>\n";

		$out .= "<meta name=\"twitter:card\" content=\"summary_large_image\">";
		$out .= sprintf("<meta name=\"twitter:title\" content=\"%s\">", strip_tags($title));
		$out .= sprintf("<meta name=\"twitter:description\" content=\"%s\">", htmlentities(strip_tags($description)));
		$out .= sprintf("<meta name=\"twitter:image\" content=\"%s\">", $image);

		$out .= sprintf(
			"<link rel=\"icon\" type=\"image/ico\" href=\"%simg/favicon.png\" />\n", 
			$this->public
		);

		$out .= sprintf(
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"%scss/web/google.css?v=%s\" />\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);
		
		$out .= sprintf(
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"%scss/web/bootstrap.min.css?v=%s\" />\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);

		$out .= sprintf(
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"%scss/web/font-awesome.css?v=%s\" />\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);

		$out .= sprintf(
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"%scss/web/style.css?v=%s\" />\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);


		$out .= sprintf(
			"<script src=\"%sjs/web/jquery-3.2.1.min.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/bootstrap.min.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/scripts.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
			$this->public,
			Config::WEBSITE_VERSION
		);

		if(isset($_SESSION["URL"][1]) && ($_SESSION["URL"][1]=="palezni-informacia" || $_SESSION["URL"][1]=="view")){
			$out .= sprintf(
				"<link rel=\"stylesheet\" href=\"%sphotoswipe/photoswipe.css?v=%s\" /> \n", 
				$this->public,
				Config::WEBSITE_VERSION
			);

			$out .= sprintf(
				"<link rel=\"stylesheet\" href=\"%sphotoswipe/default-skin.css?v=%s\" /> \n", 
				$this->public,
				Config::WEBSITE_VERSION
			);

			$out .= sprintf(
				"<script src=\"%sphotoswipe/photoswipe.min.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
				$this->public,
				Config::WEBSITE_VERSION
			);

			$out .= sprintf(
				"<script src=\"%sphotoswipe/photoswipe-ui-default.min.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
				$this->public,
				Config::WEBSITE_VERSION
			);
		}

        if(isset($_SESSION["URL"][1]) && $_SESSION["URL"][1]=="varianti"){
        	$out .= sprintf(
				"<link rel=\"stylesheet\" type=\"text/css\" href=\"%scss/web/bootstrap-datepicker.css?v=%s\" />\n", 
				$this->public,
				Config::WEBSITE_VERSION
			);

        	$out .= sprintf(
				"<script src=\"%sjs/web/bootstrap-datepicker.js?v=%s\" type=\"text/javascript\" charset=\"utf-8\"></script>\n", 
				$this->public,
				Config::WEBSITE_VERSION
			);
        }

        $out .= sprintf(
			"<script src=\"//code.jivosite.com/widget/h7SxYQZH1Z\" async></script>\n"
		);

		
		$out .= "</head>\n";
		$out .= "<body>\n";	
		
		
		return $out;
	}
}